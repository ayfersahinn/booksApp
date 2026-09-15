<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::create([
            'category_id' => 1,
            'publisher_id' => 1,
            'title' => 'Suç ve Ceza',
            'author' => 'Fyodor Dostoyevski',
            'isbn' => '9789754589024',
            'description' => 'İnsanın vicdanı, ahlaki sorumluluğu ve suç kavramı üzerine klasik bir roman.',
            'pages' => 687,
            'cover_image' => null,
            'published_year' => 1866,
        ]);

        Book::create([
            'category_id' => 1,
            'publisher_id' => 2,
            'title' => 'Kürk Mantolu Madonna',
            'author' => 'Sabahattin Ali',
            'isbn' => '9789750738600',
            'description' => 'Raif Efendi ve Maria Puder arasındaki unutulmaz aşkı anlatan Türk edebiyatı klasiği.',
            'pages' => 160,
            'cover_image' => null,
            'published_year' => 1943,
        ]);

        Book::create([
            'category_id' => 2,
            'publisher_id' => 4,
            'title' => 'Dune',
            'author' => 'Frank Herbert',
            'isbn' => '9786053754799',
            'description' => 'Uzak bir gelecekte Arrakis gezegeninde geçen epik bir bilim kurgu romanı.',
            'pages' => 688,
            'cover_image' => null,
            'published_year' => 1965,
        ]);

        Book::create([
            'category_id' => 3,
            'publisher_id' => 1,
            'title' => 'Nutuk',
            'author' => 'Mustafa Kemal Atatürk',
            'isbn' => '9786254050668',
            'description' => 'Türkiye Cumhuriyeti’nin kuruluş sürecini anlatan temel tarih eserlerinden biri.',
            'pages' => 600,
            'cover_image' => null,
            'published_year' => 1927,
        ]);

        Book::create([
            'category_id' => 4,
            'publisher_id' => 3,
            'title' => 'Sokrates\'in Savunması',
            'author' => 'Platon',
            'isbn' => '9789753639960',
            'description' => 'Sokrates’in mahkemede yaptığı savunmayı konu alan klasik felsefe eseri.',
            'pages' => 96,
            'cover_image' => null,
            'published_year' => 1700,
        ]);

        Book::create([
            'category_id' => 5,
            'publisher_id' => 6,
            'title' => 'Atomik Alışkanlıklar',
            'author' => 'James Clear',
            'isbn' => '9786052995780',
            'description' => 'Küçük alışkanlıkların zaman içinde büyük sonuçlara dönüşmesini anlatan kişisel gelişim kitabı.',
            'pages' => 320,
            'cover_image' => null,
            'published_year' => 2018,
        ]);

        Book::create([
            'category_id' => 6,
            'publisher_id' => 5,
            'title' => 'Kırlangıç Çığlığı',
            'author' => 'Ahmet Ümit',
            'isbn' => '9789750522609',
            'description' => 'Başkomiser Nevzat’ın İstanbul’da çözdüğü karmaşık bir cinayet soruşturmasını anlatan polisiye roman.',
            'pages' => 504,
            'cover_image' => null,
            'published_year' => 2018,
        ]);

        Book::create([
            'category_id' => 7,
            'publisher_id' => 1,
            'title' => 'Steve Jobs',
            'author' => 'Walter Isaacson',
            'isbn' => '9786053320018',
            'description' => 'Apple’ın kurucusu Steve Jobs’ın hayatını ve kariyerini anlatan biyografi.',
            'pages' => 656,
            'cover_image' => null,
            'published_year' => 2011,
        ]);

        Book::create([
            'category_id' => 8,
            'publisher_id' => 7,
            'title' => 'Küçük Prens',
            'author' => 'Antoine de Saint-Exupéry',
            'isbn' => '9789754372454',
            'description' => 'Bir çocuğun gezegenler arasındaki yolculuğu üzerinden dostluk ve yaşam üzerine düşündüren klasik eser.',
            'pages' => 96,
            'cover_image' => null,
            'published_year' => 1943,
        ]);

        Book::create([
            'category_id' => 1,
            'publisher_id' => 2,
            'title' => 'İnce Memed',
            'author' => 'Yaşar Kemal',
            'isbn' => '9789750807112',
            'description' => 'Çukurova’da geçen, eşitsizlik ve adaletsizliğe karşı mücadeleyi anlatan Türk edebiyatı klasiği.',
            'pages' => 436,
            'cover_image' => null,
            'published_year' => 1955,
        ]);
    }
}
