<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\ImageOptimizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Resolve product image to public /media/ URL (do not use /storage for media).
     */
    private function resolveProductImageUrl(?string $image): ?string
    {
        if (empty($image) || ! is_string($image)) {
            return null;
        }
        // If stored as absolute URL, keep as-is.
        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }
        $path = preg_replace('#^/?(storage/|public/|media/)?#', '', trim($image));
        $path = str_replace('\\', '/', $path);
        if ($path === '') {
            return null;
        }
        return '/media/' . $path;
    }

    public function index()
    {
        $products = Product::with('category:id,name,slug,parent_id', 'category.parent:id,name')
            ->orderBy('name')
            ->paginate(15);
        $products->getCollection()->transform(function ($product) {
            $arr = $product->toArray();
            $arr['image_url'] = $this->resolveProductImageUrl($product->image);
            return $arr;
        });
        return Inertia::render('Admin/Products/Index', ['products' => $products]);
    }

    public function create()
    {
        $categories = Category::where('status', true)
            ->with('parent:id,name')
            ->orderByRaw('COALESCE(parent_id, id)')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'parent_id']);
        return Inertia::render('Admin/Products/Form', [
            'product'    => null,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'       => 'required|exists:categories,id',
            'name'              => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'stock'             => 'nullable|integer|min:0',
            'status'            => 'boolean',
            'condition'         => 'required|in:new,pre_owned',
            'image'             => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gallery.*'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'image.required' => 'Please upload a main image for the product.',
            'image.image'    => 'The main image must be an image (JPEG, PNG, JPG or WebP).',
            'image.max'      => 'The main image may not be larger than 5 MB.',
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->boolean('status', true);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $storedPath = null;
            $storedDisk = 'media';
            try {
                // Ensure base dirs exist (some hosts block implicit mkdir).
                if (! Storage::disk('media')->exists('products')) {
                    Storage::disk('media')->makeDirectory('products');
                }
                $storedPath = $file->store('products', 'media');
            } catch (\Throwable $e) {
                // Fallback for hosts where public/ is not writable.
                $storedDisk = 'public';
                $storedPath = $file->store('products', 'public');
            }
            if ($storedPath) {
                $validated['image'] = $storedPath;
                $disk = Storage::disk($storedDisk);
                app(ImageOptimizationService::class)->optimizeFile($disk->path($storedPath));
            }
        }

        $gallery = [];
        $galleryFiles = $request->file('gallery');
        if ($galleryFiles) {
            $galleryFiles = is_array($galleryFiles) ? $galleryFiles : [$galleryFiles];
            $imageService = app(ImageOptimizationService::class);
            foreach ($galleryFiles as $file) {
                if ($file) {
                    $stored = null;
                    $storedDisk = 'media';
                    try {
                        if (! Storage::disk('media')->exists('products/gallery')) {
                            Storage::disk('media')->makeDirectory('products/gallery');
                        }
                        $stored = $file->store('products/gallery', 'media');
                    } catch (\Throwable $e) {
                        $storedDisk = 'public';
                        $stored = $file->store('products/gallery', 'public');
                    }
                    if ($stored) {
                        $gallery[] = $stored;
                        $imageService->optimizeFile(Storage::disk($storedDisk)->path($stored));
                    }
                }
            }
        }
        $validated['gallery'] = $gallery;

        Product::create($validated);
        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        $product->load('category:id,name,slug,parent_id');
        $categories = Category::where('status', true)
            ->with('parent:id,name')
            ->orderByRaw('COALESCE(parent_id, id)')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'parent_id']);
        return Inertia::render('Admin/Products/Form', [
            'product'    => $product,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id'       => 'required|exists:categories,id',
            'name'              => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'stock'             => 'nullable|integer|min:0',
            'status'            => 'boolean',
            'condition'         => 'required|in:new,pre_owned',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gallery.*'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->boolean('status', true);
        $validated['price'] = (float) $validated['price'];
        $validated['stock'] = isset($validated['stock']) ? (int) $validated['stock'] : null;

        // IMPORTANT: When editing a product, the admin form always includes `image: null`
        // (because we use forceFormData). If we pass `image => null` into update(),
        // the existing product image is cleared. Only update the image when a new file is uploaded.
        if (! $request->hasFile('image')) {
            unset($validated['image']);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $storedPath = null;
            $storedDisk = 'media';
            try {
                if (! Storage::disk('media')->exists('products')) {
                    Storage::disk('media')->makeDirectory('products');
                }
                $storedPath = $file->store('products', 'media');
            } catch (\Throwable $e) {
                $storedDisk = 'public';
                $storedPath = $file->store('products', 'public');
            }
            if ($storedPath) {
                $validated['image'] = $storedPath;
                app(ImageOptimizationService::class)->optimizeFile(Storage::disk($storedDisk)->path($storedPath));
            }
        }

        $galleryFiles = $request->file('gallery');
        if ($galleryFiles) {
            $gallery = $product->gallery ?? [];
            $imageService = app(ImageOptimizationService::class);
            $galleryFiles = is_array($galleryFiles) ? $galleryFiles : [$galleryFiles];
            foreach ($galleryFiles as $file) {
                if ($file) {
                    $stored = null;
                    $storedDisk = 'media';
                    try {
                        if (! Storage::disk('media')->exists('products/gallery')) {
                            Storage::disk('media')->makeDirectory('products/gallery');
                        }
                        $stored = $file->store('products/gallery', 'media');
                    } catch (\Throwable $e) {
                        $storedDisk = 'public';
                        $stored = $file->store('products/gallery', 'public');
                    }
                    if ($stored) {
                        $gallery[] = $stored;
                        $imageService->optimizeFile(Storage::disk($storedDisk)->path($stored));
                    }
                }
            }
            $validated['gallery'] = $gallery;
        }

        $product->update($validated);
        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    public function toggleFeatured(Product $product)
    {
        try {
            $product->update(['is_featured' => ! $product->is_featured]);
            return redirect()->back()->with('success', $product->is_featured ? 'Product marked as featured.' : 'Product unmarked as featured.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Could not update featured status. Please try again.');
        }
    }
}
