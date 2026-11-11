<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Occasion;
use App\Models\Branch;
use App\Models\Color;
use App\Models\FlowerType;
use App\Models\Tag;
use App\Models\Setting;
use App\Models\Flower;
use App\Models\FlowerImage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Admin
        User::firstOrCreate(
            ['email' => 'admin@ekashop.com'],
            [
                'name' => 'Admin EkaShop',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // 2. Data Pengaturan
        $settings = [
            ['key' => 'main_whatsapp_number', 'value' => '6281234567890'],
            ['key' => 'store_name', 'value' => 'EkaShop'],
            ['key' => 'min_pickup_hours', 'value' => '3'],
        ];
        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], ['value' => $setting['value']]);
        }

        // 3. Kategori Sesuai Nama File Gambar Anda
        $categories = [
            'Arimbi',
            'Artificial Flower',
            'Buket Boneka',
            'Buket Skripsi',
            'Bunga Meja & Hand Bouq Fresh',
            'Bunga Meja Artificial',
            'Decor Mobil Pengantin',
            'Dewisri',
            'Hand Bouquet Artificial',
            'Hydrangea Edition',
            'Matahari Edition',
            'Money Bouq',
            'Satin',
            'Snack Buket',
            'Snack Tower',
            'Srikandi',
            'Thumbelina & Round',
            'Thumbelina',
            'With Chocolate',
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => str()->slug($cat)], ['name' => $cat]);
        }

        // 4. Data Acara (Occasion)
        $occasions = ['Ulang Tahun', 'Pernikahan', 'Duka Cita', 'Wisuda', 'Valentine', 'Anniversary'];
        foreach ($occasions as $occ) {
            Occasion::firstOrCreate(['slug' => str()->slug($occ)], ['name' => $occ]);
        }

        // 5. Data Cabang Aktif
        Branch::firstOrCreate(
            ['name' => 'Cabang Purwokerto Utama'],
            [
                'address' => 'Jl. Jend. Sudirman No. 10, Purwokerto',
                'maps_url' => 'https://maps.google.com/?q=Purwokerto',
                'whatsapp_number' => '6281234567890',
                'opening_hours' => '08:00 - 20:00',
                'is_active' => true
            ]
        );

        // 6. Produk Berdasarkan Nama File Gambar Katalog Beserta Acara Terkait
        $catalogItems = [
            ['cat' => 'Arimbi', 'file' => 'Arimbi.jpg', 'name' => 'Arimbi Signature Series', 'occasion' => 'Anniversary'],
            ['cat' => 'Artificial Flower', 'file' => 'Artificial flower(1).jpg', 'name' => 'Artificial Flower Collection 1', 'occasion' => 'Ulang Tahun'],
            ['cat' => 'Artificial Flower', 'file' => 'Artificial flower(2).jpg', 'name' => 'Artificial Flower Collection 2', 'occasion' => 'Valentine'],
            ['cat' => 'Artificial Flower', 'file' => 'Artificial flower(3).jpg', 'name' => 'Artificial Flower Collection 3', 'occasion' => 'Anniversary'],
            ['cat' => 'Buket Boneka', 'file' => 'Buket Boneka(1).jpg', 'name' => 'Buket Boneka Wisuda 1', 'occasion' => 'Wisuda'],
            ['cat' => 'Buket Boneka', 'file' => 'Buket Boneka(2).jpg', 'name' => 'Buket Boneka Wisuda 2', 'occasion' => 'Wisuda'],
            ['cat' => 'Buket Boneka', 'file' => 'Buket Boneka(3).jpg', 'name' => 'Buket Boneka Wisuda 3', 'occasion' => 'Wisuda'],
            ['cat' => 'Buket Skripsi', 'file' => 'Buket skripsi.jpg', 'name' => 'Buket Sidang Skripsi Eksklusif', 'occasion' => 'Wisuda'],
            ['cat' => 'Bunga Meja & Hand Bouq Fresh', 'file' => 'Bunga meja & hand bouq fresh.jpg', 'name' => 'Fresh Table Flower & Hand Bouquet', 'occasion' => 'Ulang Tahun'],
            ['cat' => 'Bunga Meja Artificial', 'file' => 'Bunga meja artificial.jpg', 'name' => 'Bunga Meja Artificial Elegan', 'occasion' => 'Anniversary'],
            ['cat' => 'Decor Mobil Pengantin', 'file' => 'Decor mobil pengantin.jpg', 'name' => 'Wedding Car Decoration', 'occasion' => 'Pernikahan'],
            ['cat' => 'Dewisri', 'file' => 'Dewisri.jpg', 'name' => 'Dewisri Graceful Series', 'occasion' => 'Valentine'],
            ['cat' => 'Hand Bouquet Artificial', 'file' => 'Hand bouquet artficial.jpg', 'name' => 'Hand Bouquet Artificial', 'occasion' => 'Ulang Tahun'],
            ['cat' => 'Hydrangea Edition', 'file' => 'Hydrangea edition.jpg', 'name' => 'Hydrangea Edition Bouquet', 'occasion' => 'Anniversary'],
            ['cat' => 'Matahari Edition', 'file' => 'Matahari edition.jpg', 'name' => 'Matahari Edition (Sunflower)', 'occasion' => 'Ulang Tahun'],
            ['cat' => 'Money Bouq', 'file' => 'Money Bouq(1).jpg', 'name' => 'Luxury Money Bouquet Package', 'occasion' => 'Ulang Tahun'],
            ['cat' => 'Satin', 'file' => 'Satin.jpg', 'name' => 'Satin Ribbon Elegant Series', 'occasion' => 'Wisuda'],
            ['cat' => 'Snack Buket', 'file' => 'Snack buket.jpg', 'name' => 'Custom Snack Bouquet', 'occasion' => 'Ulang Tahun'],
            ['cat' => 'Snack Tower', 'file' => 'Snack Tower.jpg', 'name' => 'Birthday Snack Tower', 'occasion' => 'Ulang Tahun'],
            ['cat' => 'Srikandi', 'file' => 'Srikandi.jpg', 'name' => 'Srikandi Luxury Series', 'occasion' => 'Anniversary'],
            ['cat' => 'Thumbelina & Round', 'file' => 'Thumbelina & Round.jpg', 'name' => 'Thumbelina & Round Box Series', 'occasion' => 'Valentine'],
            ['cat' => 'Thumbelina', 'file' => 'Thumbelina.jpg', 'name' => 'Thumbelina Compact Series', 'occasion' => 'Valentine'],
            ['cat' => 'With Chocolate', 'file' => 'With chocolate.jpg', 'name' => 'Sweet Chocolate & Flower Package', 'occasion' => 'Valentine'],
        ];

        foreach ($catalogItems as $item) {
            $category = Category::where('name', $item['cat'])->first();
            $occasion = Occasion::where('name', $item['occasion'])->first();

            if ($category) {
                $flower = Flower::firstOrCreate(
                    ['slug' => str()->slug($item['name'])],
                    [
                        'category_id' => $category->id,
                        'name' => $item['name'],
                        'description' => 'Koleksi resmi ' . $item['name'] . ' dari katalog eksklusif EKASHOP.',
                        'price' => 175000,
                        'discount_price' => 150000,
                        'status' => 'active',
                        'is_featured' => true,
                    ]
                );

                // Sinkronkan relasi ke tabel pivot occasions
                if ($occasion) {
                    $flower->occasions()->sync([$occasion->id]);
                }

                FlowerImage::firstOrCreate(
                    ['flower_id' => $flower->id, 'is_primary' => true],
                    ['path' => 'flowers/' . $item['file']]
                );
            }
        }
    }
}