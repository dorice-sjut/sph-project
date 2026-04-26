<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $farmer = User::where('email', 'farmer@agrosphere.com')->first();

        if (!$farmer) {
            return;
        }

        $products = [
            [
                'name' => 'Fresh Maize',
                'description' => 'High-quality yellow maize, freshly harvested. Perfect for milling or direct consumption.',
                'category' => 'Grains',
                'price' => 25.00,
                'price_unit' => 'kg',
                'quantity' => 500,
                'quantity_unit' => 'kg',
                'location' => 'Arusha, Tanzania',
                'is_organic' => false,
                'status' => 'available',
                'images' => ['https://images.unsplash.com/photo-1551754655-cd27e38d2076?w=600&h=600&fit=crop'],
            ],
            [
                'name' => 'Organic Tomatoes',
                'description' => 'Fresh, juicy organic tomatoes grown without pesticides.',
                'category' => 'Vegetables',
                'price' => 15.00,
                'price_unit' => 'kg',
                'quantity' => 200,
                'quantity_unit' => 'kg',
                'location' => 'Arusha, Tanzania',
                'is_organic' => true,
                'status' => 'available',
                'images' => ['https://images.unsplash.com/photo-1546094096-0df4bcaaa337?w=600&h=600&fit=crop'],
            ],
            [
                'name' => 'Green Beans',
                'description' => 'Crisp and fresh green beans, perfect for cooking.',
                'category' => 'Vegetables',
                'price' => 12.00,
                'price_unit' => 'kg',
                'quantity' => 150,
                'quantity_unit' => 'kg',
                'location' => 'Arusha, Tanzania',
                'is_organic' => false,
                'status' => 'available',
                'images' => ['https://images.unsplash.com/photo-1567375683735-28839303d805?w=600&h=600&fit=crop'],
            ],
            [
                'name' => 'Avocados',
                'description' => 'Ripe Hass avocados, creamy and delicious.',
                'category' => 'Fruits',
                'price' => 30.00,
                'price_unit' => 'kg',
                'quantity' => 100,
                'quantity_unit' => 'kg',
                'location' => 'Arusha, Tanzania',
                'is_organic' => true,
                'status' => 'available',
                'images' => ['https://images.unsplash.com/photo-1523049673857-eb18f1d7b578?w=600&h=600&fit=crop'],
            ],
            [
                'name' => 'Red Onions',
                'description' => 'Quality red onions, great for cooking and salads.',
                'category' => 'Vegetables',
                'price' => 8.00,
                'price_unit' => 'kg',
                'quantity' => 300,
                'quantity_unit' => 'kg',
                'location' => 'Arusha, Tanzania',
                'is_organic' => false,
                'status' => 'available',
                'images' => ['https://images.unsplash.com/photo-1618512496248-a07fe83aa8cb?w=600&h=600&fit=crop'],
            ],
            [
                'name' => 'Pigeon Peas',
                'description' => 'Dried pigeon peas (ndengu), perfect for traditional dishes.',
                'category' => 'Legumes',
                'price' => 45.00,
                'price_unit' => 'kg',
                'quantity' => 250,
                'quantity_unit' => 'kg',
                'location' => 'Arusha, Tanzania',
                'is_organic' => false,
                'status' => 'available',
                'images' => ['https://images.unsplash.com/photo-1615485290382-441e4d049cb5?w=600&h=600&fit=crop'],
            ],
            [
                'name' => 'Fresh Milk',
                'description' => 'Fresh cow milk delivered daily.',
                'category' => 'Dairy',
                'price' => 18.00,
                'price_unit' => 'litre',
                'quantity' => 100,
                'quantity_unit' => 'litre',
                'location' => 'Arusha, Tanzania',
                'is_organic' => true,
                'status' => 'available',
                'images' => ['https://images.unsplash.com/photo-1563636619-e9143da7973b?w=600&h=600&fit=crop'],
            ],
            [
                'name' => 'Bananas',
                'description' => 'Sweet cooking bananas (matoke).',
                'category' => 'Fruits',
                'price' => 10.00,
                'price_unit' => 'bunch',
                'quantity' => 80,
                'quantity_unit' => 'bunch',
                'location' => 'Arusha, Tanzania',
                'is_organic' => false,
                'status' => 'available',
                'images' => ['https://images.unsplash.com/photo-1481349518771-20055b2a7b24?w=600&h=600&fit=crop'],
            ],
        ];

        foreach ($products as $productData) {
            $farmer->products()->create($productData);
        }
    }
}
