<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Roman',
            'slug' => 'roman'
        ]);
        Category::create([
            'name' => 'Bilim kurgu',
            'slug' => 'bilim-kurgu'
        ]);
        Category::create([
            'name' => 'Tarih',
            'slug' => 'tarih'
        ]);
        Category::create([
            'name' => 'Felsefe',
            'slug' => 'felsefe'
        ]);

        Category::create([
            'name' => 'Kişisel Gelişim',
            'slug' => 'kisisel-gelisim'
        ]);
        Category::create([
            'name' => 'Polisiye',
            'slug' => 'polisiye'
        ]);
        Category::create([
            'name' => 'Biyografi',
            'slug' => 'biyografi'
        ]);
        Category::create([
            'name' => 'Çocuk',
            'slug' => 'cocuk'
        ]);
    }
}
