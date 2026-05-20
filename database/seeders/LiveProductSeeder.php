<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LiveProductSeeder extends Seeder
{
    public function run(): void
    {
        $productsData = [
            [
                'name' => 'Nike Air Force 1 \'07',
                'category' => 'Men',
                'price' => 2800000,
                'original_price' => 3500000,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/b7d9211c-26e7-431a-ac24-b0540fb3c00f/AIR+FORCE+1+%2707.png',
                'featured_position' => 'hero',
                'description' => 'The radiance lives on in the Nike Air Force 1 \'07, a clean icon rebuilt for everyday movement.',
            ],
            [
                'name' => 'Nike Air Max 270',
                'category' => 'Men',
                'price' => 3200000,
                'original_price' => 4200000,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/9d58ca09-3252-4e00-8b17-38435d8a8b84/AIR+MAX+270.png',
                'featured_position' => 'secondary',
                'description' => 'Nike lifestyle cushioning with a bold Air unit and a sharp everyday profile.',
            ],
            [
                'name' => 'Nike Dunk Low Retro',
                'category' => 'Men',
                'price' => 2500000,
                'original_price' => 2900000,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/8e97f699-245c-4433-875f-3ee0a1f49615/NIKE+DUNK+LOW+RETRO.png',
                'featured_position' => 'secondary',
                'description' => 'A hardwood classic brought back with crisp overlays and low-profile street energy.',
            ],
            [
                'name' => 'Nike Air Max Pulse',
                'category' => 'Men',
                'price' => 3900000,
                'original_price' => null,
                'image_url' => '/images/hero.png',
                'featured_position' => 'secondary',
                'description' => 'A dark, athletic Air Max statement built around the original homepage product asset.',
            ],
        ];

        Product::query()
            ->whereNotNull('featured_position')
            ->where(function ($query): void {
                $query->where('image_url', 'like', 'https://images.unsplash.com/%')
                    ->orWhere('image_url', 'like', '%placeholder%')
                    ->orWhereNull('image_url')
                    ->orWhere('image_url', '');
            })
            ->update(['featured_position' => null]);

        Product::query()
            ->where('featured_position', 'hero')
            ->where('name', '!=', 'Nike Air Force 1 \'07')
            ->update(['featured_position' => null]);

        foreach ($productsData as $data) {
            $category = Category::where('name', $data['category'])->first();

            if (! $category) {
                $category = Category::create([
                    'name' => $data['category'],
                    'slug' => Str::slug($data['category']),
                    'description' => "Products for {$data['category']}",
                ]);
            }

            $product = Product::updateOrCreate(
                ['name' => $data['name']],
                [
                    'category_id' => $category->id,
                    'slug' => Str::slug($data['name']),
                    'description' => $data['description'],
                    'price' => $data['price'],
                    'original_price' => $data['original_price'],
                    'image_url' => $data['image_url'],
                    'featured_position' => $data['featured_position'],
                    'status' => 'active',
                ]
            );

            foreach (['US 7', 'US 8', 'US 9', 'US 10', 'US 11', 'US 12'] as $size) {
                $skuBase = strtoupper(substr(str_replace('-', '', $product->slug), 0, 14));
                $sizeCode = str_replace(' ', '', $size);

                ProductVariant::updateOrCreate(
                    ['sku' => "NK-{$skuBase}-{$sizeCode}"],
                    [
                        'product_id' => $product->id,
                        'size' => $size,
                        'color' => 'Black/White',
                        'stock' => 50,
                    ]
                );
            }

            $this->command->info("Seeded homepage catalog product: {$data['name']}");
        }
    }
}
