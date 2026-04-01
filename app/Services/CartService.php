<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const SESSION_KEY = 'cart';

    /**
     * @return array<int, array{product_id: int, name: string, slug: string, price: float, quantity: int, image_url: string|null}>
     */
    public function getItems(): array
    {
        $raw = Session::get(self::SESSION_KEY, []);
        $items = [];
        foreach ($raw as $row) {
            $id = (int) ($row['product_id'] ?? 0);
            if ($id > 0) {
                $items[] = [
                    'product_id' => $id,
                    'name' => (string) ($row['name'] ?? ''),
                    'slug' => (string) ($row['slug'] ?? ''),
                    'price' => (float) ($row['price'] ?? 0),
                    'quantity' => max(1, (int) ($row['quantity'] ?? 1)),
                    'image_url' => isset($row['image_url']) ? (string) $row['image_url'] : null,
                ];
            }
        }
        return $items;
    }

    public function getCount(): int
    {
        return array_reduce($this->getItems(), fn (int $sum, array $item) => $sum + $item['quantity'], 0);
    }

    public function getSubtotal(): float
    {
        return array_reduce(
            $this->getItems(),
            fn (float $sum, array $item) => $sum + ($item['price'] * $item['quantity']),
            0.0
        );
    }

    public function add(int $productId, int $quantity = 1): void
    {
        $product = Product::where('id', $productId)->where('status', true)->first();
        if (! $product) {
            return;
        }
        $price = (float) $product->price;
        if ($price < 0) {
            $price = 0;
        }
        $quantity = max(1, $quantity);
        if ($product->stock !== null && $product->stock < $quantity) {
            $quantity = max(0, (int) $product->stock);
        }
        if ($quantity === 0) {
            return;
        }

        $imageUrl = $product->image
            ? '/media/' . preg_replace('#^/?(storage/|public/|media/)?#', '', trim(str_replace('\\', '/', $product->image)))
            : null;

        $items = $this->getItems();
        $found = false;
        foreach ($items as $i => $item) {
            if ((int) $item['product_id'] === $productId) {
                $newQty = $item['quantity'] + $quantity;
                if ($product->stock !== null && $product->stock < $newQty) {
                    $newQty = (int) $product->stock;
                }
                $items[$i]['quantity'] = $newQty;
                $items[$i]['price'] = $price;
                $items[$i]['name'] = $product->name;
                $items[$i]['slug'] = $product->slug;
                $found = true;
                break;
            }
        }
        if (! $found) {
            $items[] = [
                'product_id' => $productId,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $price,
                'quantity' => $quantity,
                'image_url' => $imageUrl,
            ];
        }
        $this->save($items);
    }

    public function update(int $productId, int $quantity): void
    {
        if ($quantity < 1) {
            $this->remove($productId);
            return;
        }
        $product = Product::find($productId);
        if ($product && $product->stock !== null && $product->stock < $quantity) {
            $quantity = (int) $product->stock;
        }
        $items = $this->getItems();
        foreach ($items as $i => $item) {
            if ((int) $item['product_id'] === $productId) {
                $items[$i]['quantity'] = $quantity;
                if ($product) {
                    $items[$i]['price'] = (float) $product->price;
                    $items[$i]['name'] = $product->name;
                    $items[$i]['slug'] = $product->slug;
                }
                $this->save($items);
                return;
            }
        }
    }

    public function remove(int $productId): void
    {
        $items = array_values(array_filter($this->getItems(), fn (array $item) => (int) $item['product_id'] !== $productId));
        $this->save($items);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /**
     * @param array<int, array{product_id: int, name: string, slug: string, price: float, quantity: int, image_url: string|null}> $items
     */
    private function save(array $items): void
    {
        Session::put(self::SESSION_KEY, $items);
    }
}
