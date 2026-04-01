<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ImageOptimizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query()->whereNull('parent_id');

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', '%' . $q . '%')
                    ->orWhere('slug', 'like', '%' . $q . '%');
            });
        }

        $categories = $query->orderBy('sort_order')->orderBy('name')->get();

        return Inertia::render('Admin/Categories/Index', [
            'categories' => $categories,
            'filters'   => [
                'q' => $request->input('q'),
            ],
        ]);
    }

    public function subcategories(Request $request, Category $category)
    {
        $query = Category::query()->where('parent_id', $category->id);

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', '%' . $q . '%')
                    ->orWhere('slug', 'like', '%' . $q . '%');
            });
        }

        $subcategories = $query->orderBy('sort_order')->orderBy('name')->get();

        return Inertia::render('Admin/Categories/Subcategories', [
            'parentCategory' => $category,
            'subcategories'  => $subcategories,
            'filters'        => [
                'q' => $request->input('q'),
            ],
        ]);
    }

    public function create(Request $request)
    {
        $categories = Category::with('parent:id,name')->orderBy('sort_order')->orderBy('name')->get(['id', 'name', 'parent_id']);
        $parentId = $request->input('parent_id') ? (int) $request->input('parent_id') : null;
        return Inertia::render('Admin/Categories/Form', [
            'category'  => null,
            'categories' => $categories,
            'presetParentId' => $parentId,
        ]);
    }

    public function store(Request $request)
    {
        $request->merge(['parent_id' => $request->input('parent_id') ?: null]);
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'boolean',
            'parent_id'   => 'nullable|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->boolean('status', true);
        $parentId = $validated['parent_id'] ?? null;
        $validated['sort_order'] = (int) Category::where('parent_id', $parentId)->max('sort_order') + 1;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'media');
            app(ImageOptimizationService::class)->optimizeFile(Storage::disk('media')->path($validated['image']));
        }

        $new = Category::create($validated);
        $parentId = $new->parent_id;
        if ($parentId) {
            return redirect()->route('admin.categories.subcategories', $parentId)->with('success', 'Sub-category created.');
        }
        return redirect()->route('admin.categories.index')->with('success', 'Category created.');
    }

    public function edit(Category $category)
    {
        $category->load('parent:id,name');
        $categories = Category::where('id', '!=', $category->id)->with('parent:id,name')->orderBy('sort_order')->orderBy('name')->get(['id', 'name', 'parent_id']);
        return Inertia::render('Admin/Categories/Form', ['category' => $category, 'categories' => $categories]);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->boolean('status', true);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'media');
            app(ImageOptimizationService::class)->optimizeFile(Storage::disk('media')->path($validated['image']));
        }

        $category->update($validated);
        $parentId = $category->parent_id;
        if ($parentId) {
            return redirect()->route('admin.categories.subcategories', $parentId)->with('success', 'Sub-category updated.');
        }
        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $parentId = $category->parent_id;
        $category->delete();
        if ($parentId) {
            return redirect()->route('admin.categories.subcategories', $parentId)->with('success', 'Sub-category deleted.');
        }
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'parent_id'   => 'nullable|exists:categories,id',
            'ordered_ids' => 'required|array',
            'ordered_ids.*' => 'exists:categories,id',
        ]);
        $parentId = $validated['parent_id'] ?? null;
        $orderedIds = $validated['ordered_ids'];
        foreach ($orderedIds as $index => $id) {
            Category::where('id', $id)->where('parent_id', $parentId)->update(['sort_order' => $index]);
        }
        $returnTo = $request->input('return_to');
        if ($returnTo === 'subcategories' && $parentId) {
            return redirect()->route('admin.categories.subcategories', (int) $parentId)->withQueryString()->with('success', 'Order updated.');
        }
        $query = array_filter(['q' => $request->input('q')]);
        return redirect()->route('admin.categories.index', $query ?: [])->with('success', 'Order updated.');
    }
}
