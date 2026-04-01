<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('products:clear', function () {
    $count = Product::count();
    if ($count === 0) {
        $this->info('No products to delete.');
        return;
    }
    Product::query()->delete();
    $this->info("Deleted {$count} product" . ($count === 1 ? '' : 's') . '.');
})->purpose('Delete all products');

Artisan::command('categories:clear', function () {
    $count = Category::count();
    if ($count === 0) {
        $this->info('No categories to delete.');
        return;
    }
    \Illuminate\Support\Facades\DB::table('products')->whereNotNull('category_id')->update(['category_id' => null]);
    Category::query()->delete();
    $this->info("Deleted {$count} categor" . ($count === 1 ? 'y' : 'ies') . '.');
})->purpose('Delete all categories and sub-categories (products are kept with no category)');
