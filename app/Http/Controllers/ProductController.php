<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Return a relative URL for an image in public/media/ (do not use /storage for media).
     */
    private function resolveProductImageUrl(?string $image): ?string
    {
        if (empty($image) || ! is_string($image)) {
            return null;
        }
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

    public function index(Request $request)
    {
        $categorySlug = $request->query('category');
        $all = Category::where('status', true)
            ->with('parent:id,name,slug')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'parent_id']);
        $byParent = $all->groupBy('parent_id');
        $topLevel = $byParent->get(null, collect())->values();
        $categories = collect();
        foreach ($topLevel as $parent) {
            $categories->push($parent);
            $categories = $categories->merge($byParent->get($parent->id, collect()));
        }
        $categories = $categories->values()->all();

        $query = Product::with('category:id,name,slug,parent_id', 'category.parent:id,name,slug')
            ->where('status', true);

        if ($categorySlug && $categorySlug !== '') {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        $products = $query->orderBy('name')
            ->get(['id', 'category_id', 'name', 'slug', 'short_description', 'image', 'price'])
            ->map(function ($product) {
                $arr = $product->toArray();
                $arr['image_url'] = $this->resolveProductImageUrl($product->image);
                return $arr;
            });

        return Inertia::render('Products/Index', [
            'title'      => 'Products',
            'categories' => $categories,
            'products'   => $products,
            'filterCategory' => $categorySlug,
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::with('category:id,name,slug')
            ->where('status', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $imageUrl = $this->resolveProductImageUrl($product->image);
        $galleryUrls = [];
        if (is_array($product->gallery) && count($product->gallery) > 0) {
            foreach ($product->gallery as $path) {
                if ($path && is_string($path)) {
                    $normalized = preg_replace('#^/?(storage/|public/|media/)?#', '', trim($path));
                    $normalized = str_replace('\\', '/', $normalized);
                    if ($normalized !== '') {
                        $galleryUrls[] = '/media/' . $normalized;
                    }
                }
            }
        }

        return Inertia::render('Products/Show', [
            'title'   => $product->name,
            'product' => array_merge($product->only([
                'id', 'name', 'slug', 'short_description', 'description',
                'image', 'gallery', 'category', 'price',
            ]), [
                'image_url'   => $imageUrl,
                'gallery_urls' => $galleryUrls,
            ]),
        ]);
    }
}
