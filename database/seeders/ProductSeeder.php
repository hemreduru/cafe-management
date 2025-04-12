<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Sıcak İçecekler (Kategori ID: 1)
            [
                'name' => 'Türk Kahvesi',
                'description' => 'Geleneksel Türk kahvesi, orta şekerli',
                'category_id' => 1,
                'price' => 25.00,
                'stock' => 100
            ],
            [
                'name' => 'Filtre Kahve',
                'description' => 'Günlük öğütülmüş taze filtre kahve',
                'category_id' => 1,
                'price' => 30.00,
                'stock' => 100
            ],
            [
                'name' => 'Latte',
                'description' => 'Espresso ve buharla ısıtılmış süt',
                'category_id' => 1,
                'price' => 35.00,
                'stock' => 100
            ],
            [
                'name' => 'Cappuccino',
                'description' => 'Espresso, buharla ısıtılmış süt ve süt köpüğü',
                'category_id' => 1,
                'price' => 35.00,
                'stock' => 100
            ],
            [
                'name' => 'Çay',
                'description' => 'Demli siyah çay',
                'category_id' => 1,
                'price' => 15.00,
                'stock' => 200
            ],
            
            // Soğuk İçecekler (Kategori ID: 2)
            [
                'name' => 'Soğuk Kahve',
                'description' => 'Buzlu soğuk demleme kahve',
                'category_id' => 2,
                'price' => 40.00,
                'stock' => 50
            ],
            [
                'name' => 'Limonata',
                'description' => 'Ev yapımı taze limonata',
                'category_id' => 2,
                'price' => 28.00,
                'stock' => 80
            ],
            [
                'name' => 'Milkshake',
                'description' => 'Çikolatalı milkshake',
                'category_id' => 2,
                'price' => 45.00,
                'stock' => 40
            ],
            
            // Kahvaltı (Kategori ID: 3)
            [
                'name' => 'Serpme Kahvaltı',
                'description' => '2 kişilik zengin serpme kahvaltı tabağı',
                'category_id' => 3,
                'price' => 200.00,
                'stock' => 30
            ],
            [
                'name' => 'Menemen',
                'description' => 'Domates, biber ve yumurta ile hazırlanan menemen',
                'category_id' => 3,
                'price' => 65.00,
                'stock' => 50
            ],
            [
                'name' => 'Omlet',
                'description' => 'Peynirli omlet, yanında ekmek ile',
                'category_id' => 3,
                'price' => 55.00,
                'stock' => 50
            ],
            
            // Ana Yemekler (Kategori ID: 4)
            [
                'name' => 'Köfte',
                'description' => '150gr ızgara köfte, patates kızartması ve salata ile',
                'category_id' => 4,
                'price' => 120.00,
                'stock' => 40
            ],
            [
                'name' => 'Tavuk Şiş',
                'description' => 'Izgara tavuk şiş, pilav ve közlenmiş sebze ile',
                'category_id' => 4,
                'price' => 110.00,
                'stock' => 40
            ],
            [
                'name' => 'Makarna',
                'description' => 'Domates soslu fettuccine makarna, parmesan peyniri ile',
                'category_id' => 4,
                'price' => 90.00,
                'stock' => 45
            ],
            
            // Tatlılar (Kategori ID: 5)
            [
                'name' => 'Cheesecake',
                'description' => 'Ev yapımı frambuazlı cheesecake',
                'category_id' => 5,
                'price' => 60.00,
                'stock' => 25
            ],
            [
                'name' => 'Tiramisu',
                'description' => 'İtalyan usulü tiramisu',
                'category_id' => 5,
                'price' => 65.00,
                'stock' => 20
            ],
            [
                'name' => 'Sufle',
                'description' => 'Sıcak çikolatalı sufle, vanilyalı dondurma ile',
                'category_id' => 5,
                'price' => 70.00,
                'stock' => 20
            ],
            
            // Aperatifler (Kategori ID: 6)
            [
                'name' => 'Patates Kızartması',
                'description' => 'Taze kızartılmış patates',
                'category_id' => 6,
                'price' => 45.00,
                'stock' => 100
            ],
            [
                'name' => 'Soğan Halkası',
                'description' => 'Çıtır soğan halkaları, özel sosuyla',
                'category_id' => 6,
                'price' => 50.00,
                'stock' => 80
            ],
            [
                'name' => 'Nachos',
                'description' => 'Cheddar peynirli nachos, guacamole ve salsa ile',
                'category_id' => 6,
                'price' => 75.00,
                'stock' => 60
            ],
            
            // Alkolsüz İçecekler (Kategori ID: 7)
            [
                'name' => 'Kola',
                'description' => '330ml kutu kola',
                'category_id' => 7,
                'price' => 20.00,
                'stock' => 200
            ],
            [
                'name' => 'Soda',
                'description' => '250ml sade soda',
                'category_id' => 7,
                'price' => 15.00,
                'stock' => 200
            ],
            [
                'name' => 'Meyve Suyu',
                'description' => '300ml şişe portakal suyu',
                'category_id' => 7,
                'price' => 25.00,
                'stock' => 150
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
