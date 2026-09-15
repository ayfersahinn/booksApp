<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Publisher::create([
            'name' => 'Türkiye İş Bankası Kültür Yayınları',
            'slug' => 'is-bankasi-kultur-yayinlari'
        ]);
        Publisher::create([
            'name' => 'Can Yayınları',
            'slug' => 'can-yayinlari'
        ]);
        Publisher::create([
            'name' => 'Yapı Kredi Yayınları',
            'slug' => 'yapi-kredi'
        ]);
        Publisher::create([
            'name' => 'İthaki Yayınları',
            'slug' => 'ithaki'
        ]);
        Publisher::create([
            'name' => 'İletişim Yayınları',
            'slug' => 'iletisim'
        ]);
        Publisher::create([
            'name' => 'Pegasus Yayınları',
            'slug' => 'pegasus'
        ]);
        Publisher::create([
            'name' => 'Ötüken Yayınları',
            'slug' => 'otuken'
        ]);
    }
}
