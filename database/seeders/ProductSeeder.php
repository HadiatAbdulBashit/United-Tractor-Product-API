<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                "name" => "Iphone 16 Pro Max",
                "price" => 25_000_000,
                "category_product_id" => 1,
                "image" => "https://images.unsplash.com/photo-1664115170143-92dde9c5b54c"
            ],
            [
                "name" => "Samsung S24 Ultra",
                "price" => 20_000_000,
                "category_product_id" => 1,
                "image" => "https://images.unsplash.com/photo-1713027420493-e675245ea725"
            ],
            [
                "name" => "Lenovo Yoga Pro 9i",
                "price" => 10_000_000,
                "category_product_id" => 2,
                "image" => "https://p1-ofp.static.pub//fes/cms/2024/06/21/wztzju2kpodk8thxwlg7lfrfvsem92879151.png"
            ],
            [
                "name" => "ROG Strix SCAR 18 (2024)",
                "price" => 75_000_000,
                "category_product_id" => 2,
                "image" => "https://dlcdnwebimgs.asus.com/gain/43819DB5-4690-4506-9472-35708C42457C/w717/h525/w273"
            ],
            [
                "name" => "Samsung Tab S9 Ultra",
                "price" => 16_000_000,
                "category_product_id" => 3,
                "image" => "https://images.samsung.com/id/galaxy-tab-s9/buy/product_color_tabS9_ultra_graphite.jpg"
            ],
        ];

        foreach ($products as $data) {
            Product::create($data);
        }
    }
}
