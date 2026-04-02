<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));
        $limit = (int) $request->query('limit', 8);
        $limit = max(1, min(12, $limit));

        if ($q === '' || mb_strlen($q) < 2) {
            return response()->json([
                'query' => $q,
                'categories' => [],
                'products' => [],
            ]);
        }

        $products = Product::query()
            ->where('status', true)
            ->where(function ($qry) use ($q) {
                $qry->where('name', 'like', '%' . $q . '%')
                    ->orWhere('slug', 'like', '%' . $q . '%')
                    ->orWhere('short_description', 'like', '%' . $q . '%');
            })
            ->orderBy('name')
            ->limit($limit)
            ->get(['id', 'name', 'slug', 'price']);

        $categories = [];
        if (class_exists(Category::class)) {
            $categories = Category::query()
                ->where('status', true)
                ->where(function ($qry) use ($q) {
                    $qry->where('name', 'like', '%' . $q . '%')
                        ->orWhere('slug', 'like', '%' . $q . '%');
                })
                ->orderBy('name')
                ->limit(6)
                ->get(['id', 'name', 'slug', 'parent_id'])
                ->map(fn ($c) => $c->only(['id', 'name', 'slug', 'parent_id']))
                ->values()
                ->all();
        }

        return response()->json([
            'query' => $q,
            'categories' => $categories,
            'products' => $products->map(fn ($p) => $p->only(['id', 'name', 'slug', 'price']))->values()->all(),
        ]);
    }
}

