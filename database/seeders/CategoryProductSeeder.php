<?php

namespace Database\Seeders;

use App\Models\CategoryProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $categoryProduct = [
            "HP",
            "Laptop",
            "Tablet"
        ];

        foreach ($categoryProduct as $data) {
            CategoryProduct::create([
                "name" => $data,
            ]);
        }
    }
}
