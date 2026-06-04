<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class LiveProductSeeder extends Seeder
{
    /**
     * @var array<int, User>|null
     */
    private ?array $reviewUsers = null;

    public function run(): void
    {
        $productsData = [
            [
                'name' => "Nike Air Force 1 '07",
                'parent_category' => 'Men',
                'category' => 'Lifestyle',
                'price' => 2800000,
                'original_price' => 3500000,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/b7d9211c-26e7-431a-ac24-b0540fb3c00f/AIR+FORCE+1+%2707.png',
                'featured_position' => 'hero',
                'description' => 'The radiance lives on in the Nike Air Force 1 \'07, a clean icon rebuilt for everyday movement.',
                'type' => 'shoes',
            ],
            [
                'name' => 'Nike Air Max 270',
                'parent_category' => 'Men',
                'category' => 'Lifestyle',
                'price' => 3200000,
                'original_price' => 4200000,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/9d58ca09-3252-4e00-8b17-38435d8a8b84/AIR+MAX+270.png',
                'featured_position' => 'secondary',
                'description' => 'Nike lifestyle cushioning with a bold Air unit and a sharp everyday profile.',
                'type' => 'shoes',
            ],
            [
                'name' => 'Nike Dunk Low Retro',
                'parent_category' => 'Men',
                'category' => 'Lifestyle',
                'price' => 2500000,
                'original_price' => 2900000,
                'image_url' => 'https://static.nike.com/a/images/t_web_pdp_936_v2/f_auto%2Cu_9ddf04c7-2a9a-4d76-add1-d15af8f0263d%2Cc_scale%2Cfl_relative%2Cw_1.0%2Ch_1.0%2Cfl_layer_apply/dbd2620b-a99f-4279-97db-0344edf84e31/NIKE%2BDUNK%2BLOW%2BRETRO.png',
                'featured_position' => 'secondary',
                'description' => 'A hardwood classic brought back with crisp overlays and low-profile street energy.',
                'type' => 'shoes',
            ],
            [
                'name' => 'Nike Air Max Pulse',
                'parent_category' => 'Men',
                'category' => 'Lifestyle',
                'price' => 3900000,
                'original_price' => null,
                'image_url' => '/images/hero.png',
                'featured_position' => 'secondary',
                'description' => 'A dark, athletic Air Max statement built around the original homepage product asset.',
                'type' => 'shoes',
            ],
            [
                'name' => 'Nike Pegasus 41',
                'parent_category' => 'Men',
                'category' => 'Running',
                'price' => 3790000,
                'original_price' => 4100000,
                'image_url' => 'https://static.nike.com/a/images/t_web_pdp_936_v2/f_auto%2Cu_9ddf04c7-2a9a-4d76-add1-d15af8f0263d%2Cc_scale%2Cfl_relative%2Cw_1.0%2Ch_1.0%2Cfl_layer_apply/d7df4815-2375-4608-8d2a-1772a7d7ad03/AIR%2BZOOM%2BPEGASUS%2B41.png',
                'featured_position' => null,
                'description' => 'Responsive cushioning in the Pegasus 41 provides an energized ride for everyday road running.',
                'type' => 'shoes',
            ],
            [
                'name' => 'Nike Vaporfly 3',
                'parent_category' => 'Men',
                'category' => 'Running',
                'price' => 6990000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/1c6d36e2-2e06-4447-b86a-9b165b4f6edc/ZOOMX+VAPORFLY+NEXT%2525253B3.png',
                'featured_position' => null,
                'description' => 'Catch \'em if you can. Giving you race-day speed to conquer any distance, the Nike Vaporfly 3 is built for the chase.',
                'type' => 'shoes',
            ],
            [
                'name' => 'Nike Invincible 3',
                'parent_category' => 'Men',
                'category' => 'Running',
                'price' => 5290000,
                'original_price' => 5790000,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/cd8e1d5d-c6a6-4b95-a50d-df4b3f1c1f7a/INVINCIBLE+3.png',
                'featured_position' => null,
                'description' => 'With maximum cushioning to support every mile, the Invincible 3 gives you our highest level of comfort underfoot.',
                'type' => 'shoes',
            ],
            [
                'name' => 'Nike G.T. Cut 3',
                'parent_category' => 'Men',
                'category' => 'Basketball',
                'price' => 5490000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/256ee6ff-e24c-47ea-a9c0-c4e9cb4e4db5/GT+CUT+3.png',
                'featured_position' => null,
                'description' => 'Designed for space-makers and baseline-cutters, the G.T. Cut 3 helps you stop on a dime and accelerate into the lane.',
                'type' => 'shoes',
            ],
            [
                'name' => 'LeBron XXI',
                'parent_category' => 'Men',
                'category' => 'Basketball',
                'price' => 5990000,
                'original_price' => 6590000,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/7862594a-38fa-4e00-84c4-722a45a32508/LEBRON+XXI+EP.png',
                'featured_position' => null,
                'description' => 'The LeBron XXI features a cabling system that works with Zoom Air cushioning for a low-to-the-ground, lightweight feel.',
                'type' => 'shoes',
            ],
            [
                'name' => 'Nike Metcon 9',
                'parent_category' => 'Men',
                'category' => 'Training',
                'price' => 3990000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/244bf37d-b7d6-4447-9759-3fb7e7b68181/METCON+9.png',
                'featured_position' => null,
                'description' => 'Whatever your "why" is for working out, the Metcon 9 makes it all worth it with an enlarged Hyperlift plate.',
                'type' => 'shoes',
            ],
            [
                'name' => 'Nike Club Fleece Hoodie',
                'parent_category' => 'Men',
                'category' => 'Lifestyle',
                'price' => 1800000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/456722ea-c6f3-4f99-9ee4-59e355c70753/M+NK+CLUB+FLC+OB+HDY.png',
                'featured_position' => null,
                'description' => 'Club Fleece sweatshirts are universally loved for their coziness and consistency.',
                'type' => 'clothing',
            ],
            [
                'name' => 'Nike Dri-FIT DNA Basketball Shorts',
                'parent_category' => 'Men',
                'category' => 'Basketball',
                'price' => 1200000,
                'original_price' => 1500000,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/60f4eb78-c0b0-466f-b25f-2ff77df517e4/M+NK+DF+DNA+SHORT.png',
                'featured_position' => null,
                'description' => 'Celebrate the history of the hardwood with these lightweight, sweat-wicking DNA shorts.',
                'type' => 'clothing',
            ],
            [
                'name' => 'Nike Vomero 17',
                'parent_category' => 'Women',
                'category' => 'Running',
                'price' => 4790000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/8c69be07-160a-4fb4-87cf-44f2d72bc46d/W+VOMERO+17.png',
                'featured_position' => null,
                'description' => 'A springy and soft ride to fuel every mile, the Vomero 17 assists in your running journey.',
                'type' => 'shoes',
            ],
            [
                'name' => 'Nike Pegasus EasyOn Women',
                'parent_category' => 'Women',
                'category' => 'Running',
                'price' => 3790000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/a1e2f3d6-4447-4cf0-94e8-f9d226a27e7f/W+PEGASUS+41+EASYON.png',
                'featured_position' => null,
                'description' => 'Designed with the same reliable feel, this version has a quick-entry system so you can get moving fast.',
                'type' => 'shoes',
            ],
            [
                'name' => "Nike Blazer Mid '77 Vintage",
                'parent_category' => 'Women',
                'category' => 'Lifestyle',
                'price' => 2800000,
                'original_price' => 3300000,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/df6920f8-e5e5-4df0-94d3-73138b0ee2eb/BLAZER+MID+%2777+VINTAGE.png',
                'featured_position' => null,
                'description' => 'Styled for the \'70s. Loved in the \'80s. Classic in the \'90s. Ready for what\'s next.',
                'type' => 'shoes',
            ],
            [
                'name' => 'Nike Air Max Excee Women',
                'parent_category' => 'Women',
                'category' => 'Lifestyle',
                'price' => 2700000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/ea26c361-b541-4cf1-9be5-ff99c424072f/W+AIR+MAX+EXCEE.png',
                'featured_position' => null,
                'description' => 'Inspired by the Air Max 90, the Nike Air Max Excee is a celebration of a classic through a new lens.',
                'type' => 'shoes',
            ],
            [
                'name' => 'Nike Free Metcon 6 Women',
                'parent_category' => 'Women',
                'category' => 'Training',
                'price' => 3990000,
                'original_price' => 4500000,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/4c919d36-fa11-4770-bc2f-e8b23f2f8cd9/W+FREE+METCON+6.png',
                'featured_position' => null,
                'description' => 'Flexibility meets stability. The Free Metcon 6 provides a lightweight and supportive fit for your gym routines.',
                'type' => 'shoes',
            ],
            [
                'name' => 'Nike Zenvy Gentle-Support Tights',
                'parent_category' => 'Women',
                'category' => 'Yoga',
                'price' => 2500000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/e81794aa-34bc-4cf2-83b8-c304d207f2a1/W+NK+ZENVY+TIGHT.png',
                'featured_position' => null,
                'description' => 'Designed for yoga, pilates and barre, our Zenvy tights feature InfinaSoft fabric for ultimate softness.',
                'type' => 'clothing',
            ],
            [
                'name' => 'Nike Alate Minimalist Bra',
                'parent_category' => 'Women',
                'category' => 'Yoga',
                'price' => 1200000,
                'original_price' => 1500000,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/3452efcd-ab32-4cf0-bb4d-ef335cd712c4/W+NK+ALATE+MINIMALIST+BRA.png',
                'featured_position' => null,
                'description' => 'A minimalist bra offering light support and maximum breathability for low-intensity sessions.',
                'type' => 'clothing',
            ],
            [
                'name' => 'Nike Sportswear Club Fleece Sweatshirt Women',
                'parent_category' => 'Women',
                'category' => 'Lifestyle',
                'price' => 1800000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/fdfbdf78-c0b0-466f-b25f-2ff77df517e4/W+NK+CLUB+FLC+CREW.png',
                'featured_position' => null,
                'description' => 'Bring standard comfort and warmth to your daily style with this classic cotton crew.',
                'type' => 'clothing',
            ],
            [
                'name' => 'Nike Force 1 Low EasyOn Kids',
                'parent_category' => 'Kids',
                'category' => 'Shoes',
                'price' => 1500000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/281fbf78-e5e5-4df0-94d3-73138b0ee2eb/FORCE+1+LOW+EASYON+PS.png',
                'featured_position' => null,
                'description' => 'The iconic AF1 styled for kids with an easy hook-and-loop strap system.',
                'type' => 'shoes',
            ],
            [
                'name' => 'Nike Air Max Dn Kids',
                'parent_category' => 'Kids',
                'category' => 'Shoes',
                'price' => 2200000,
                'original_price' => 2700000,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/a78cbf78-fa11-4770-bc2f-e8b23f2f8cd9/AIR+MAX+DN+PS.png',
                'featured_position' => null,
                'description' => 'Dynamic Air unit system sized down for kids to jump, run and play all day.',
                'type' => 'shoes',
            ],
            [
                'name' => 'Nike Pegasus 41 Kids',
                'parent_category' => 'Kids',
                'category' => 'Shoes',
                'price' => 2000000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/d2e2f3d6-4447-4cf0-94e8-f9d226a27e7f/PEGASUS+41+GS.png',
                'featured_position' => null,
                'description' => 'Provides active youngsters with the responsiveness and stability they need for school sports.',
                'type' => 'shoes',
            ],
            [
                'name' => 'Nike Sportswear Club Fleece Hoodie Kids',
                'parent_category' => 'Kids',
                'category' => 'Clothing',
                'price' => 1100000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/ff3cbdf8-c0b0-466f-b25f-2ff77df517e4/K+NK+CLUB+FLC+HDY.png',
                'featured_position' => null,
                'description' => 'Comfortable fleece fit designed to handle playtime and chill time alike.',
                'type' => 'clothing',
            ],
            [
                'name' => 'Nike Dri-FIT Tee Kids',
                'parent_category' => 'Kids',
                'category' => 'Clothing',
                'price' => 650000,
                'original_price' => 800000,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/678cbf78-e5e5-4df0-94d3-73138b0ee2eb/K+NK+DF+TEE.png',
                'featured_position' => null,
                'description' => 'Sweat-wicking Dri-FIT fabric keeps kids cool and comfortable during sports.',
                'type' => 'clothing',
            ],
            [
                'name' => 'Nike Kids Everyday Cushioned Socks',
                'parent_category' => 'Kids',
                'category' => 'Accessories',
                'price' => 350000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/a567df78-c0b0-466f-b25f-2ff77df517e4/K+NK+EVERYDAY+SOCK+3P.png',
                'featured_position' => null,
                'description' => 'Extra cushioning under the heel and forefoot keeps active kids moving comfortably.',
                'type' => 'accessories',
            ],
            [
                'name' => 'Nike Kids Classic Backpack',
                'parent_category' => 'Kids',
                'category' => 'Accessories',
                'price' => 850000,
                'original_price' => 1100000,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/b678df78-fa11-4770-bc2f-e8b23f2f8cd9/K+NK+CLASSIC+BKPK.png',
                'featured_position' => null,
                'description' => 'Sized just right for school and weekend adventures with multiple zip pockets.',
                'type' => 'accessories',
            ],
            [
                'name' => 'Nike Apex Bucket Hat',
                'parent_category' => 'Kids',
                'category' => 'Accessories',
                'price' => 750000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/df78be07-160a-4fb4-87cf-44f2d72bc46d/K+NK+APEX+BUCKET.png',
                'featured_position' => null,
                'description' => 'Retro mid-depth bucket design offers 360 degrees of lightweight sun coverage.',
                'type' => 'accessories',
            ],
            [
                'name' => 'Nike Everyday Cushioned Crew Socks (3 Pairs)',
                'parent_category' => 'Men',
                'category' => 'Training',
                'price' => 450000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/345bbf78-fa11-4770-bc2f-e8b23f2f8cd9/M+NK+EVRDY+CUSH+3P.png',
                'featured_position' => null,
                'description' => 'Power through your workout with socks featuring sweat-wicking technology and extra comfort.',
                'type' => 'accessories',
            ],
            [
                'name' => 'Nike Heritage Waistpack',
                'parent_category' => 'Men',
                'category' => 'Lifestyle',
                'price' => 650000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/d3e2f3d6-e5e5-4df0-94d3-73138b0ee2eb/NK+HERITAGE+WAISTPACK.png',
                'featured_position' => null,
                'description' => 'A comfortable strap makes the Nike Heritage Waistpack a breeze for everyday trips.',
                'type' => 'accessories',
            ],
            [
                'name' => 'Nike Gym Club Bag',
                'parent_category' => 'Women',
                'category' => 'Training',
                'price' => 950000,
                'original_price' => 1200000,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/a1e2f3d6-fa11-4770-bc2f-e8b23f2f8cd9/W+NK+GYM+CLUB.png',
                'featured_position' => null,
                'description' => 'Designed to fit all your training gear with a durable outer layer.',
                'type' => 'accessories',
            ],
            [
                'name' => 'Nike Sportswear Futura T-Shirt',
                'parent_category' => 'Men',
                'category' => 'Lifestyle',
                'price' => 750000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/8c69be07-c0b0-466f-b25f-2ff77df517e4/M+NK+TEE+FUTURA.png',
                'featured_position' => null,
                'description' => 'Classic cotton fabric has a soft and lightweight feel for casual daily wear.',
                'type' => 'clothing',
            ],
            [
                'name' => 'Nike Dri-FIT Challenger Shorts',
                'parent_category' => 'Men',
                'category' => 'Running',
                'price' => 950000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/4c919d36-e5e5-4df0-94d3-73138b0ee2eb/M+NK+DF+CHALLENGER+SHRT.png',
                'featured_position' => null,
                'description' => 'Run with comfort and dry feel in these lightweight, sweat-wicking running shorts.',
                'type' => 'clothing',
            ],
            [
                'name' => 'Nike Dri-FIT Legend Tee',
                'parent_category' => 'Men',
                'category' => 'Training',
                'price' => 650000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/244bf37d-fa11-4770-bc2f-e8b23f2f8cd9/M+NK+DF+LEGEND+TEE.png',
                'featured_position' => null,
                'description' => 'An athletic fit built with lightweight material that keeps you fresh during workouts.',
                'type' => 'clothing',
            ],
            [
                'name' => 'Nike Sportswear Club Fleece Joggers',
                'parent_category' => 'Men',
                'category' => 'Lifestyle',
                'price' => 1600000,
                'original_price' => 1950000,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/df6920f8-c0b0-466f-b25f-2ff77df517e4/M+NK+CLUB+FLC+JGR.png',
                'featured_position' => null,
                'description' => 'Combines a classic jogger silhouette with soft fleece for an elevated everyday look.',
                'type' => 'clothing',
            ],
            [
                'name' => 'Nike Pro Tights Women',
                'parent_category' => 'Women',
                'category' => 'Training',
                'price' => 1100000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/ea26c361-fa11-4770-bc2f-e8b23f2f8cd9/W+NP+TIGHT.png',
                'featured_position' => null,
                'description' => 'Stretchy, high-waist support with sweat-wicking fabrics for intense exercise.',
                'type' => 'clothing',
            ],
        ];

        $productsData = array_map(
            fn (array $data): array => $this->enrichedProductData($data),
            array_merge($productsData, $this->phaseSevenShoeProducts())
        );

        // Clean up unsafe legacy product imagery so old seeded rows cannot leak back into the storefront.
        Product::query()
            ->where(function ($query): void {
                $query->where('image_url', 'like', 'https://images.unsplash.com/%')
                    ->orWhere('image_url', 'like', '%placeholder%')
                    ->orWhere('image_url', 'like', 'https://images.nike.com/%')
                    ->orWhereNull('image_url')
                    ->orWhere('image_url', '');
            })
            ->update([
                'image_url' => '/images/placeholders/lifestyle.svg',
                'featured_position' => null,
            ]);

        // Clean up other featured products that do not match the new catalog items.
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
            ->where('name', '!=', "Nike Air Force 1 '07")
            ->update(['featured_position' => null]);

        $hasSku = Schema::hasColumn('product_variants', 'sku');
        $hasProductStory = Schema::hasColumn('products', 'product_story');
        $hasHighlights = Schema::hasColumn('products', 'highlights');
        $hasCareInstructions = Schema::hasColumn('products', 'care_instructions');
        $hasProductReviews = Schema::hasTable('product_reviews');

        foreach ($productsData as $data) {
            $parentSlug = Str::slug($data['parent_category']);
            $parentName = $data['parent_category'];
            $childSlug = Str::slug($parentName.'-'.$data['category']);
            $childName = $data['category'];

            // Ensure parent category exists
            $parent = Category::firstOrCreate(
                ['slug' => $parentSlug],
                ['name' => $parentName, 'description' => "Shop for $parentName"]
            );

            // Ensure child category exists
            $category = Category::firstOrCreate(
                ['slug' => $childSlug],
                [
                    'name' => $childName,
                    'parent_id' => $parent->id,
                    'description' => "$childName gear for $parentName",
                ]
            );

            $productSlug = Str::slug($data['name']);

            $productPayload = [
                'name' => $data['name'],
                'category_id' => $category->id,
                'description' => $this->localizedDescription($data),
                'price' => $data['price'],
                'original_price' => $data['original_price'],
                'image_url' => $data['image_url'],
                'featured_position' => $data['featured_position'],
                'status' => 'active',
            ];

            if ($hasProductStory) {
                $productPayload['product_story'] = $data['product_story'];
            }

            if ($hasHighlights) {
                $productPayload['highlights'] = $data['highlights'];
            }

            if ($hasCareInstructions) {
                $productPayload['care_instructions'] = $data['care_instructions'];
            }

            // Create or update the product.
            $product = Product::updateOrCreate(
                ['slug' => $productSlug],
                $productPayload
            );

            // Determine sizes based on type
            if (isset($data['sizes'])) {
                $sizes = $data['sizes'];
            } elseif ($data['type'] === 'shoes' && $parentSlug === 'kids') {
                $sizes = ['US 11C', 'US 12C', 'US 13C', 'US 1Y', 'US 2Y', 'US 3Y'];
            } elseif ($data['type'] === 'shoes') {
                $sizes = ['US 7', 'US 8', 'US 9', 'US 10', 'US 11', 'US 12'];
            } elseif ($data['type'] === 'clothing') {
                $sizes = ['S', 'M', 'L', 'XL'];
            } else {
                $sizes = ['One Size'];
            }

            // Assign standard colors
            $colors = $data['colors'] ?? ['Black/White'];

            // Populate variants idempotently
            foreach ($colors as $color) {
                foreach ($sizes as $size) {
                    $skuBase = strtoupper(substr((string) preg_replace('/[^A-Za-z0-9]/', '', Str::ascii($product->slug)), 0, 10));
                    $sizeCode = strtoupper((string) preg_replace('/[^A-Za-z0-9]/', '', Str::ascii($size)));
                    $colorCode = strtoupper(substr((string) preg_replace('/[^A-Za-z0-9]/', '', Str::ascii($color)), 0, 3)) ?: 'CLR';
                    $sku = "NK-{$skuBase}-{$sizeCode}-{$colorCode}";

                    if ($hasSku) {
                        ProductVariant::updateOrCreate(
                            ['sku' => $sku],
                            [
                                'product_id' => $product->id,
                                'size' => $size,
                                'color' => $color,
                                'stock' => 50,
                            ]
                        );
                    } else {
                        ProductVariant::updateOrCreate(
                            [
                                'product_id' => $product->id,
                                'size' => $size,
                                'color' => $color,
                            ],
                            [
                                'sku' => $sku,
                                'stock' => 50,
                            ]
                        );
                    }
                }
            }

            if ($hasProductReviews) {
                $this->seedProductReviews($product, $data);
            }

            $this->command->info("Seeded live catalog product: {$data['name']}");
        }

        Product::query()
            ->where('image_url', '/'.Product::FALLBACK_IMAGE_PATH)
            ->with('category')
            ->get()
            ->each(function (Product $product): void {
                $product->update([
                    'image_url' => $this->placeholderImageUrl([
                        'name' => $product->name,
                        'category' => $product->category?->name,
                    ]),
                ]);
            });
    }

    /**
     * Phase 7A demo shoe expansion.
     *
     * @return array<int, array<string, mixed>>
     */
    private function phaseSevenShoeProducts(): array
    {
        $placeholder = '/'.Product::FALLBACK_IMAGE_PATH;

        return [
            [
                'name' => 'Nike Air Max 90',
                'parent_category' => 'Men',
                'category' => 'Lifestyle',
                'price' => 3590000,
                'original_price' => 4090000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Thiết kế Air Max cổ điển với đệm êm, form gọn và phối màu dễ mang mỗi ngày.',
                'type' => 'shoes',
                'colors' => ['Trắng/Đen', 'Xám/Đỏ'],
            ],
            [
                'name' => 'Nike Air Max 97',
                'parent_category' => 'Men',
                'category' => 'Lifestyle',
                'price' => 4690000,
                'original_price' => 5290000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Dáng sóng đặc trưng, đệm Air toàn chiều dài và vẻ ngoài nổi bật cho phong cách phố.',
                'type' => 'shoes',
                'colors' => ['Bạc/Trắng', 'Đen/Xám'],
            ],
            [
                'name' => 'Nike Air Max Plus',
                'parent_category' => 'Men',
                'category' => 'Lifestyle',
                'price' => 4890000,
                'original_price' => null,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Mẫu Air Max mạnh mẽ với khung nâng đỡ chắc chân và diện mạo giàu năng lượng.',
                'type' => 'shoes',
                'colors' => ['Đen/Trắng', 'Xanh/Đen'],
            ],
            [
                'name' => 'Nike Air Max Excee',
                'parent_category' => 'Men',
                'category' => 'Lifestyle',
                'price' => 2890000,
                'original_price' => 3290000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Lấy cảm hứng từ Air Max 90, phù hợp cho người cần một đôi giày nhẹ và dễ phối.',
                'type' => 'shoes',
                'colors' => ['Trắng/Xám', 'Đen/Trắng'],
            ],
            [
                'name' => 'Nike Air Force 1 Low',
                'parent_category' => 'Men',
                'category' => 'Lifestyle',
                'price' => 2790000,
                'original_price' => 3190000,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/b7d9211c-26e7-431a-ac24-b0540fb3c00f/AIR+FORCE+1+%2707.png',
                'featured_position' => null,
                'description' => 'Biểu tượng sneaker đế cupsole bền bỉ, thân giày gọn và dễ dùng quanh năm.',
                'type' => 'shoes',
                'colors' => ['Trắng', 'Đen'],
            ],
            [
                'name' => 'Nike Air Force 1 Shadow',
                'parent_category' => 'Women',
                'category' => 'Lifestyle',
                'price' => 3290000,
                'original_price' => 3790000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Phiên bản AF1 nhiều lớp với đế cao hơn nhẹ, tạo điểm nhấn nữ tính và hiện đại.',
                'type' => 'shoes',
                'colors' => ['Trắng/Kem', 'Hồng/Trắng'],
                'sizes' => ['US 5', 'US 6', 'US 7', 'US 8', 'US 9'],
            ],
            [
                'name' => 'Nike Dunk Low Panda',
                'parent_category' => 'Men',
                'category' => 'Lifestyle',
                'price' => 2990000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_web_pdp_936_v2/f_auto%2Cu_9ddf04c7-2a9a-4d76-add1-d15af8f0263d%2Cc_scale%2Cfl_relative%2Cw_1.0%2Ch_1.0%2Cfl_layer_apply/dbd2620b-a99f-4279-97db-0344edf84e31/NIKE%2BDUNK%2BLOW%2BRETRO.png',
                'featured_position' => null,
                'description' => 'Phối màu trắng đen dễ nhận diện, form Dunk thấp cổ hợp với nhiều kiểu trang phục.',
                'type' => 'shoes',
                'colors' => ['Trắng/Đen'],
            ],
            [
                'name' => 'Nike Dunk High Retro',
                'parent_category' => 'Men',
                'category' => 'Lifestyle',
                'price' => 3290000,
                'original_price' => 3690000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Dáng cổ cao lấy cảm hứng bóng rổ cổ điển, chắc chân và nổi bật khi phối đồ.',
                'type' => 'shoes',
                'colors' => ['Đen/Trắng', 'Đỏ/Trắng'],
            ],
            [
                'name' => "Nike Blazer Mid '77",
                'parent_category' => 'Men',
                'category' => 'Lifestyle',
                'price' => 2890000,
                'original_price' => null,
                'image_url' => 'https://static.nike.com/a/images/t_web_pdp_936_v2/f_auto/fb7eda3c-5ac8-4d05-a18f-1c2c5e82e36e/BLAZER%2BMID%2B%2777%2BVNTG.png',
                'featured_position' => null,
                'description' => 'Cổ trung, thân giày tối giản và chất retro rõ nét cho phong cách thường ngày.',
                'type' => 'shoes',
                'colors' => ['Trắng/Đen', 'Kem/Xám'],
            ],
            [
                'name' => 'Nike Court Vision Low',
                'parent_category' => 'Men',
                'category' => 'Lifestyle',
                'price' => 2090000,
                'original_price' => 2390000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Kiểu dáng sân bóng cổ điển, dễ mang và phù hợp với nhu cầu đi lại hằng ngày.',
                'type' => 'shoes',
                'colors' => ['Trắng/Đen', 'Trắng/Xanh'],
            ],
            [
                'name' => 'Nike Pegasus 40',
                'parent_category' => 'Men',
                'category' => 'Running',
                'price' => 3290000,
                'original_price' => 3890000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Giày chạy bộ ổn định, đệm phản hồi tốt và đủ bền cho lịch chạy đều đặn.',
                'type' => 'shoes',
                'colors' => ['Đen/Trắng', 'Xanh/Trắng'],
            ],
            [
                'name' => 'Nike Structure 25',
                'parent_category' => 'Men',
                'category' => 'Running',
                'price' => 3790000,
                'original_price' => 4290000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Mẫu chạy bộ hỗ trợ ổn định, phù hợp với runner cần cảm giác chắc và êm.',
                'type' => 'shoes',
                'colors' => ['Đen/Xám', 'Trắng/Xanh'],
            ],
            [
                'name' => 'Nike InfinityRN 4',
                'parent_category' => 'Women',
                'category' => 'Running',
                'price' => 4290000,
                'original_price' => 4790000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Đệm mềm, chuyển động mượt và phần thân ôm chân cho các buổi chạy dài.',
                'type' => 'shoes',
                'colors' => ['Trắng/Hồng', 'Đen/Tím'],
                'sizes' => ['US 5', 'US 6', 'US 7', 'US 8', 'US 9'],
            ],
            [
                'name' => 'Nike Free Run 5.0',
                'parent_category' => 'Women',
                'category' => 'Running',
                'price' => 2690000,
                'original_price' => 3190000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Đế linh hoạt, cảm giác tự nhiên và trọng lượng nhẹ cho chạy ngắn hoặc tập nhẹ.',
                'type' => 'shoes',
                'colors' => ['Đen/Trắng', 'Kem/Hồng'],
                'sizes' => ['US 5', 'US 6', 'US 7', 'US 8', 'US 9'],
            ],
            [
                'name' => 'Nike Flex Experience Run 12',
                'parent_category' => 'Men',
                'category' => 'Running',
                'price' => 1890000,
                'original_price' => 2290000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Giày chạy nhẹ, dễ uốn và phù hợp cho người mới bắt đầu tập luyện.',
                'type' => 'shoes',
                'colors' => ['Đen/Trắng', 'Xám/Trắng'],
            ],
            [
                'name' => 'Nike Revolution 7',
                'parent_category' => 'Women',
                'category' => 'Running',
                'price' => 1790000,
                'original_price' => 2090000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Mẫu chạy cơ bản, thoáng nhẹ và đủ êm cho tập luyện hằng ngày.',
                'type' => 'shoes',
                'colors' => ['Trắng/Xám', 'Đen/Hồng'],
                'sizes' => ['US 5', 'US 6', 'US 7', 'US 8', 'US 9'],
            ],
            [
                'name' => 'Nike Metcon 9 AMP',
                'parent_category' => 'Men',
                'category' => 'Training',
                'price' => 4290000,
                'original_price' => null,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Giày tập luyện đế chắc, hỗ trợ nâng tạ và các bài tập cường độ cao.',
                'type' => 'shoes',
                'colors' => ['Đen/Xám', 'Trắng/Đỏ'],
            ],
            [
                'name' => 'Nike Free Metcon 5',
                'parent_category' => 'Women',
                'category' => 'Training',
                'price' => 3590000,
                'original_price' => 3990000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Kết hợp độ linh hoạt và độ ổn định cho lớp tập gym, HIIT và vận động đa hướng.',
                'type' => 'shoes',
                'colors' => ['Trắng/Kem', 'Đen/Trắng'],
                'sizes' => ['US 5', 'US 6', 'US 7', 'US 8', 'US 9'],
            ],
            [
                'name' => 'Nike Air Zoom TR 1',
                'parent_category' => 'Men',
                'category' => 'Training',
                'price' => 3290000,
                'original_price' => 3690000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Đệm Zoom phản hồi nhanh, thân giày ôm chắc cho các bài tập tốc độ và sức mạnh.',
                'type' => 'shoes',
                'colors' => ['Đen/Trắng', 'Xanh/Đen'],
            ],
            [
                'name' => 'Nike Giannis Immortality 3',
                'parent_category' => 'Men',
                'category' => 'Basketball',
                'price' => 2490000,
                'original_price' => 2890000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Giày bóng rổ linh hoạt với độ bám tốt, hỗ trợ đổi hướng nhanh trên sân.',
                'type' => 'shoes',
                'colors' => ['Đen/Trắng', 'Xanh/Lục'],
            ],
            [
                'name' => 'Nike LeBron Witness 8',
                'parent_category' => 'Men',
                'category' => 'Basketball',
                'price' => 3090000,
                'original_price' => 3490000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Đệm êm, phần thân chắc và cảm giác ổn định cho người chơi bóng rổ mạnh mẽ.',
                'type' => 'shoes',
                'colors' => ['Đen/Vàng', 'Trắng/Đỏ'],
            ],
            [
                'name' => 'Nike KD Trey 5 X',
                'parent_category' => 'Men',
                'category' => 'Basketball',
                'price' => 2890000,
                'original_price' => 3290000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Mẫu bóng rổ cân bằng giữa độ êm, độ bám và khả năng di chuyển linh hoạt.',
                'type' => 'shoes',
                'colors' => ['Đen/Xám', 'Trắng/Xanh'],
            ],
            [
                'name' => 'Air Jordan 1 Low',
                'parent_category' => 'Men',
                'category' => 'Basketball',
                'price' => 3290000,
                'original_price' => 3790000,
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto%2Cq_auto%3Aeco%2Cu_126ab356-44d8-4a06-89b4-fcdcc8df0245%2Cc_scale%2Cfl_relative%2Cw_1.0%2Ch_1.0%2Cfl_layer_apply/b7e69fd2-0063-4b13-9c92-3d5967d4a526/AIR%2BJORDAN%2B1%2BLOW.png',
                'featured_position' => null,
                'description' => 'Dáng Jordan cổ thấp biểu tượng, dễ phối và phù hợp cả sân bóng lẫn đường phố.',
                'type' => 'shoes',
                'colors' => ['Trắng/Đen/Đỏ', 'Đen/Xám'],
            ],
            [
                'name' => 'Air Jordan 1 Mid',
                'parent_category' => 'Men',
                'category' => 'Basketball',
                'price' => 3590000,
                'original_price' => 4090000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Cổ trung chắc chân, giữ tinh thần Jordan cổ điển trong một form dễ mang.',
                'type' => 'shoes',
                'colors' => ['Trắng/Đỏ/Đen', 'Đen/Trắng'],
            ],
            [
                'name' => 'Nike Zion 3',
                'parent_category' => 'Men',
                'category' => 'Basketball',
                'price' => 3590000,
                'original_price' => null,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Giày bóng rổ dành cho lối chơi bùng nổ, bám sân tốt và hỗ trợ tiếp đất chắc.',
                'type' => 'shoes',
                'colors' => ['Đen/Hồng', 'Trắng/Xanh'],
            ],
            [
                'name' => 'Nike Kids Dynamo Go',
                'parent_category' => 'Kids',
                'category' => 'Shoes',
                'price' => 1290000,
                'original_price' => 1590000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Giày trẻ em dễ xỏ, nhẹ và linh hoạt cho các hoạt động ở trường hoặc sân chơi.',
                'type' => 'shoes',
                'colors' => ['Xanh/Trắng', 'Đen/Trắng'],
            ],
            [
                'name' => 'Nike Kids Flex Runner 3',
                'parent_category' => 'Kids',
                'category' => 'Shoes',
                'price' => 1390000,
                'original_price' => 1690000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Thiết kế không dây buộc, ôm chân và linh hoạt để trẻ vận động thoải mái.',
                'type' => 'shoes',
                'colors' => ['Đen/Trắng', 'Hồng/Trắng'],
            ],
            [
                'name' => 'Nike Team Hustle D 11',
                'parent_category' => 'Kids',
                'category' => 'Shoes',
                'price' => 1590000,
                'original_price' => 1890000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Giày trẻ em lấy cảm hứng bóng rổ, bám sân và đủ bền cho vận động hằng ngày.',
                'type' => 'shoes',
                'colors' => ['Trắng/Đen', 'Đỏ/Trắng'],
            ],
            [
                'name' => 'Nike Tanjun',
                'parent_category' => 'Women',
                'category' => 'Lifestyle',
                'price' => 1890000,
                'original_price' => 2190000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Thiết kế tối giản, nhẹ và êm cho những ngày cần một đôi giày thật dễ mang.',
                'type' => 'shoes',
                'colors' => ['Đen/Trắng', 'Trắng/Xám'],
                'sizes' => ['US 5', 'US 6', 'US 7', 'US 8', 'US 9'],
            ],
            [
                'name' => 'Nike Waffle Debut',
                'parent_category' => 'Women',
                'category' => 'Lifestyle',
                'price' => 2090000,
                'original_price' => 2490000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Phong cách retro với đế waffle đặc trưng, nhẹ và dễ phối đồ thường ngày.',
                'type' => 'shoes',
                'colors' => ['Kem/Nâu', 'Đen/Trắng'],
                'sizes' => ['US 5', 'US 6', 'US 7', 'US 8', 'US 9'],
            ],
            [
                'name' => 'Nike Cortez',
                'parent_category' => 'Women',
                'category' => 'Lifestyle',
                'price' => 2490000,
                'original_price' => 2890000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Biểu tượng chạy bộ cổ điển với thân giày gọn, đường nét sắc và cảm giác nhẹ chân.',
                'type' => 'shoes',
                'colors' => ['Trắng/Đỏ/Xanh', 'Đen/Trắng'],
                'sizes' => ['US 5', 'US 6', 'US 7', 'US 8', 'US 9'],
            ],
            [
                'name' => 'Nike Killshot 2',
                'parent_category' => 'Men',
                'category' => 'Lifestyle',
                'price' => 2290000,
                'original_price' => 2690000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Dáng tennis thấp cổ, phối màu sạch và hợp với phong cách tối giản.',
                'type' => 'shoes',
                'colors' => ['Trắng/Xanh', 'Trắng/Đen'],
            ],
            [
                'name' => 'Nike Motiva',
                'parent_category' => 'Women',
                'category' => 'Running',
                'price' => 2990000,
                'original_price' => 3490000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Đế rocker êm và chuyển bước mượt cho đi bộ nhanh, chạy nhẹ hoặc vận động hằng ngày.',
                'type' => 'shoes',
                'colors' => ['Kem/Hồng', 'Đen/Trắng'],
                'sizes' => ['US 5', 'US 6', 'US 7', 'US 8', 'US 9'],
            ],
            [
                'name' => 'Nike Winflo 11',
                'parent_category' => 'Men',
                'category' => 'Running',
                'price' => 2890000,
                'original_price' => 3290000,
                'image_url' => $placeholder,
                'featured_position' => null,
                'description' => 'Giày chạy bộ cân bằng giữa độ êm, độ thoáng và độ bền cho lịch tập đều.',
                'type' => 'shoes',
                'colors' => ['Đen/Trắng', 'Xám/Xanh'],
            ],
        ];
    }

    /**
     * Add presentation content and normalize image URLs before writing products.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function enrichedProductData(array $data): array
    {
        $data['image_url'] = $this->safeCatalogImageUrl($data);
        $data['description'] = $this->localizedDescription($data);
        $data['product_story'] = $data['product_story'] ?? $this->productStory($data);
        $data['highlights'] = $data['highlights'] ?? $this->productHighlights($data);
        $data['care_instructions'] = $data['care_instructions'] ?? $this->careInstructions($data);

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function safeCatalogImageUrl(array $data): string
    {
        $imageUrl = (string) ($data['image_url'] ?? '');
        $normalizedImageUrl = strtolower($imageUrl);

        if ($imageUrl === ''
            || str_contains($normalizedImageUrl, 'images.unsplash.com')
            || str_contains($normalizedImageUrl, 'placeholder')
            || str_contains($normalizedImageUrl, 'images.nike.com')
            || ltrim($imageUrl, '/') === Product::FALLBACK_IMAGE_PATH) {
            return $this->placeholderImageUrl($data);
        }

        return $imageUrl;
    }

    /**
     * Pick a local visual family asset for seeded catalog products.
     *
     * @param  array<string, mixed>  $data
     */
    private function placeholderImageUrl(array $data): string
    {
        $searchText = Str::lower(Str::ascii(implode(' ', [
            $data['name'] ?? '',
            $data['parent_category'] ?? '',
            $data['category'] ?? '',
            $data['type'] ?? '',
        ])));

        return match (true) {
            str_contains($searchText, 'air max') => '/images/placeholders/airmax.svg',
            str_contains($searchText, 'air force') => '/images/placeholders/airforce.svg',
            str_contains($searchText, 'dunk') => '/images/placeholders/dunk.svg',
            str_contains($searchText, 'jordan'),
            str_contains($searchText, 'lebron'),
            str_contains($searchText, 'giannis'),
            str_contains($searchText, 'zion'),
            str_contains($searchText, 'precision'),
            str_contains($searchText, 'basketball') => '/images/placeholders/basketball.svg',
            str_contains($searchText, 'pegasus'),
            str_contains($searchText, 'vaporfly'),
            str_contains($searchText, 'running'),
            str_contains($searchText, 'run'),
            str_contains($searchText, 'winflo'),
            str_contains($searchText, 'revolution'),
            str_contains($searchText, 'structure'),
            str_contains($searchText, 'infinity'),
            str_contains($searchText, 'motiva') => '/images/placeholders/running.svg',
            str_contains($searchText, 'metcon'),
            str_contains($searchText, 'training'),
            str_contains($searchText, 'free') => '/images/placeholders/training.svg',
            str_contains($searchText, 'kids'),
            str_contains($searchText, 'dynamo'),
            str_contains($searchText, 'flex runner'),
            str_contains($searchText, 'team hustle') => '/images/placeholders/kids.svg',
            str_contains($searchText, 'women'),
            str_contains($searchText, 'shadow'),
            str_contains($searchText, 'cortez'),
            str_contains($searchText, 'waffle'),
            str_contains($searchText, 'tanjun') => '/images/placeholders/women.svg',
            str_contains($searchText, 'clothing'),
            str_contains($searchText, 'hoodie'),
            str_contains($searchText, 'shorts'),
            str_contains($searchText, 'tights'),
            str_contains($searchText, 'fleece') => '/images/placeholders/apparel.svg',
            default => '/images/placeholders/lifestyle.svg',
        };
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function productStory(array $data): string
    {
        $category = strtolower((string) ($data['category'] ?? ''));
        $description = $this->localizedDescription($data);

        return match ($data['type'] ?? 'shoes') {
            'clothing' => "{$data['name']} được chọn cho nhịp sống thể thao hiện đại: gọn, dễ phối và đủ thoải mái để mặc nhiều giờ. {$description}",
            'accessories' => "{$data['name']} hoàn thiện bộ đồ tập hoặc ngày di chuyển với thiết kế thực dụng, nhẹ và bền. {$description}",
            default => match ($category) {
                'running' => "{$data['name']} hướng đến cảm giác chạy ổn định, êm và tự tin trên lịch tập hằng ngày. {$description}",
                'basketball' => "{$data['name']} lấy cảm hứng từ tốc độ đổi hướng trên sân, giữ form chắc và tạo điểm nhấn mạnh khi ra phố. {$description}",
                'training' => "{$data['name']} sinh ra cho những buổi tập cần độ bám, độ ổn định và cảm giác khóa chân rõ ràng. {$description}",
                default => "{$data['name']} giữ tinh thần Nike cổ điển trong một form dễ mang, dễ phối và đủ nổi bật cho nhiều hoàn cảnh. {$description}",
            },
        };
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<int, string>
     */
    private function productHighlights(array $data): array
    {
        $category = strtolower((string) ($data['category'] ?? ''));

        return match ($data['type'] ?? 'shoes') {
            'clothing' => [
                'Chất liệu mềm và dễ mặc trong nhiều khung giờ.',
                'Form thể thao gọn, phối tốt với giày lifestyle hoặc training.',
                'Phù hợp mặc hằng ngày, đi tập nhẹ hoặc di chuyển cuối tuần.',
            ],
            'accessories' => [
                'Thiết kế gọn, nhẹ và dễ mang theo.',
                'Ngăn chứa thực dụng cho đồ tập hoặc đồ cá nhân.',
                'Tông màu dễ phối với nhiều outfit Nike.',
            ],
            default => match ($category) {
                'running' => [
                    'Đệm êm, hỗ trợ chuyển bước mượt cho chạy bộ.',
                    'Upper thoáng và ôm chân vừa phải.',
                    'Đế ngoài bám ổn cho đường chạy hằng ngày.',
                ],
                'basketball' => [
                    'Độ bám tốt cho đổi hướng nhanh.',
                    'Cấu trúc thân giày giữ chân chắc khi tăng tốc.',
                    'Kiểu dáng nổi bật, dùng được cả trên sân lẫn ngoài phố.',
                ],
                'training' => [
                    'Đế chắc cho nâng tạ và bài tập đa hướng.',
                    'Cảm giác khóa chân ổn định khi chuyển động nhanh.',
                    'Vật liệu bền cho lịch tập cường độ cao.',
                ],
                default => [
                    'Form dễ mang, hợp nhiều phong cách hằng ngày.',
                    'Đệm êm và thân giày bền cho lịch di chuyển liên tục.',
                    'Phối màu sạch, dễ kết hợp quần jeans, jogger hoặc short.',
                ],
            },
        };
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function careInstructions(array $data): string
    {
        return match ($data['type'] ?? 'shoes') {
            'clothing' => 'Giặt mặt trái với màu tương đồng, dùng nước lạnh và phơi nơi thoáng mát. Hạn chế sấy nhiệt cao để giữ form vải.',
            'accessories' => 'Lau sạch bằng khăn ẩm sau khi dùng, để khô tự nhiên và tránh đặt gần nguồn nhiệt trực tiếp.',
            default => 'Lau bụi bằng bàn chải mềm sau mỗi lần mang. Với vết bẩn nhẹ, dùng khăn ẩm và xà phòng dịu; phơi nơi thoáng, không sấy nóng hoặc ngâm nước lâu.',
        };
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function seedProductReviews(Product $product, array $data): void
    {
        ProductReview::query()
            ->where('product_id', $product->id)
            ->whereNull('user_id')
            ->whereIn('author_name', ['Minh Anh', 'Quang Huy', 'Thanh Trúc'])
            ->delete();

        $reviewUsers = $this->reviewUsers();

        foreach ($this->reviewTemplates($data) as $index => $review) {
            $user = $reviewUsers[$index % count($reviewUsers)];

            ProductReview::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                ],
                [
                    'author_name' => $user->name,
                    'rating' => $review['rating'],
                    'title' => $review['title'],
                    'comment' => $review['comment'],
                    'status' => 'approved',
                ]
            );
        }
    }

    /**
     * @return array<int, User>
     */
    private function reviewUsers(): array
    {
        if ($this->reviewUsers !== null) {
            return $this->reviewUsers;
        }

        $users = [
            ['name' => 'Lan Anh', 'email' => 'review.lan-anh@example.test', 'avatar_url' => '/images/avatars/lan-anh.svg'],
            ['name' => 'Minh Khôi', 'email' => 'review.minh-khoi@example.test', 'avatar_url' => '/images/avatars/minh-khoi.svg'],
            ['name' => 'Gia Hân', 'email' => 'review.gia-han@example.test', 'avatar_url' => '/images/avatars/gia-han.svg'],
            ['name' => 'Quốc Bảo', 'email' => 'review.quoc-bao@example.test', 'avatar_url' => '/images/avatars/quoc-bao.svg'],
            ['name' => 'Hoàng Vy', 'email' => 'review.hoang-vy@example.test', 'avatar_url' => '/images/avatars/hoang-vy.svg'],
            ['name' => 'Tuấn Minh', 'email' => 'review.tuan-minh@example.test', 'avatar_url' => '/images/avatars/tuan-minh.svg'],
        ];

        $this->reviewUsers = array_map(
            fn (array $user): User => User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'avatar_url' => $user['avatar_url'],
                ]
            ),
            $users
        );

        return $this->reviewUsers;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<int, array{rating: int, title: string, comment: string}>
     */
    private function reviewTemplates(array $data): array
    {
        $name = (string) $data['name'];
        $category = strtolower((string) ($data['category'] ?? ''));

        $firstUse = match ($data['type'] ?? 'shoes') {
            'clothing' => 'mặc đi tập buổi tối, vải đứng form nhưng không bị bí.',
            'accessories' => 'đi cùng balo tập và đồ chạy bộ, dùng tiện hơn ảnh mô tả.',
            default => match ($category) {
                'running' => 'chạy 5km sau giờ làm, đệm phản hồi rõ và gót không bị cấn.',
                'basketball' => 'đánh sân trong nhà, đổi hướng chắc chân và cổ giày ôm vừa đủ.',
                'training' => 'tập deadlift nhẹ với circuit, đế ổn định và không bị trượt.',
                default => 'đi làm rồi ghé cà phê, form gọn và dễ phối với quần tối màu.',
            },
        };

        $fitNote = match ($data['type'] ?? 'shoes') {
            'clothing' => 'Size đúng bảng, ai thích mặc rộng có thể tăng một size.',
            'accessories' => 'Khóa kéo và ngăn phụ hoạt động ổn, không có cảm giác ọp ẹp.',
            default => match ($category) {
                'running' => 'Mũi giày thoáng, nên chọn đúng size thường mang nếu bàn chân không quá bè.',
                'basketball' => 'Cần break-in khoảng một buổi, sau đó phần upper mềm hơn rõ.',
                'training' => 'Ôm mu bàn chân tốt, dây giữ chắc khi chuyển bài nhanh.',
                default => 'Size khá true-to-size, phần cổ không cọ gót khi đi lâu.',
            },
        };

        return [
            [
                'rating' => 5,
                'title' => 'Trải nghiệm đúng kỳ vọng',
                'comment' => "Mình dùng {$name} để {$firstUse} {$fitNote}",
            ],
            [
                'rating' => 4,
                'title' => 'Chi tiết hoàn thiện tốt',
                'comment' => "{$name} lên chân đẹp hơn ảnh catalog, đường may đều và màu ngoài thực tế dễ mặc. Điểm mình thích nhất là cảm giác chắc nhưng không nặng.",
            ],
            [
                'rating' => 5,
                'title' => 'Hợp nhu cầu hằng ngày',
                'comment' => "Sau một tuần dùng {$name}, mình thấy sản phẩm hợp lịch di chuyển liên tục. Dễ vệ sinh, ít bám bụi và vẫn giữ form sau vài lần mang.",
            ],
        ];
    }

    /**
     * Keep legacy demo descriptions Vietnamese in the storefront.
     *
     * @param  array<string, mixed>  $data
     */
    private function localizedDescription(array $data): string
    {
        $description = (string) ($data['description'] ?? '');

        if (preg_match('/[àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđ]/iu', $description)) {
            return $description;
        }

        return match ($data['type'] ?? 'shoes') {
            'clothing' => "{$data['name']} mang lại cảm giác thoải mái, dễ phối và phù hợp cho nhịp sống thể thao hằng ngày.",
            'accessories' => "{$data['name']} là phụ kiện gọn gàng, bền bỉ và hỗ trợ tốt cho luyện tập lẫn di chuyển.",
            default => "{$data['name']} là mẫu giày Nike cân bằng giữa sự êm ái, độ bền và phong cách hiện đại cho sử dụng hằng ngày.",
        };
    }
}
