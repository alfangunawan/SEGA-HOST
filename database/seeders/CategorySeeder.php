<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Dedicated Server',
                'description' => 'Server fisik dengan sumber daya penuh yang disewakan khusus untuk satu penyewa.',
            ],
            [
                'name' => 'Virtual Private Server',
                'description' => 'Solusi VPS fleksibel yang dapat diskalakan sesuai kebutuhan proyek.',
            ],
            [
                'name' => 'Colocation',
                'description' => 'Ruang rak dengan konektivitas premium untuk menempatkan perangkat pelanggan.',
            ],
        ];

        foreach ($categories as $category) {
            $slug = Str::slug($category['name']);

            Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $category['name'],
                    'slug' => $slug,
                    'description' => $category['description'],
                ]
            );
        }
    }
}
