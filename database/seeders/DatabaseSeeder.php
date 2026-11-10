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

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Admin (Untuk Login)[cite: 1]
        User::firstOrCreate(
            ['email' => 'admin@ekashop.com'],
            [
                'name' => 'Admin EkaShop',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // 2. Data Pengaturan Default (Settings)[cite: 1, 3]
        $settings = [
            ['key' => 'main_whatsapp_number', 'value' => '6281234567890'],
            ['key' => 'store_name', 'value' => 'EkaShop'],
            ['key' => 'min_pickup_hours', 'value' => '3'], // Minimal waktu tunggu 3 jam[cite: 2]
        ];
        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], ['value' => $setting['value']]);
        }

        // 3. Data Kategori
        $categories = ['Buket Bunga', 'Standing Flower', 'Bunga Meja', 'Bunga Papan', 'Bunga Kotak'];
        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => str()->slug($cat)], ['name' => $cat]);
        }

        // 4. Data Acara (Occasion)
        $occasions = ['Ulang Tahun', 'Pernikahan', 'Duka Cita', 'Wisuda', 'Valentine', 'Anniversary'];
        foreach ($occasions as $occ) {
            Occasion::firstOrCreate(['slug' => str()->slug($occ)], ['name' => $occ]);
        }

        // 5. Data Warna (Colors)[cite: 1]
        $colors = [
            ['name' => 'Merah', 'hex' => '#FF0000'],
            ['name' => 'Putih', 'hex' => '#FFFFFF'],
            ['name' => 'Pink', 'hex' => '#FFC0CB'],
            ['name' => 'Kuning', 'hex' => '#FFFF00'],
            ['name' => 'Biru', 'hex' => '#0000FF'],
        ];
        foreach ($colors as $col) {
            Color::firstOrCreate(['hex' => $col['hex']], ['name' => $col['name']]);
        }

        // 6. Data Jenis Bunga (Flower Types)[cite: 1]
        $types = ['Mawar', 'Tulip', 'Lily', 'Anggrek', 'Matahari', 'Krisan'];
        foreach ($types as $type) {
            FlowerType::firstOrCreate(['slug' => str()->slug($type)], ['name' => $type]);
        }

        // 7. Data Tag (Tipe keyword dan mood)[cite: 1, 2]
        $tags = [
            ['name' => 'Romantis', 'type' => 'mood'],
            ['name' => 'Elegan', 'type' => 'mood'],
            ['name' => 'Ceria', 'type' => 'mood'],
            ['name' => 'Premium', 'type' => 'keyword'],
            ['name' => 'Murah', 'type' => 'keyword'],
        ];
        foreach ($tags as $tag) {
            Tag::firstOrCreate(['slug' => str()->slug($tag['name'])], [
                'name' => $tag['name'],
                'type' => $tag['type']
            ]);
        }

        // 8. Data Cabang Aktif
        Branch::firstOrCreate(
            ['name' => 'Cabang Purwokerto Utama'],
            [
                'address' => 'Jl. Jend. Sudirman No. 10, Purwokerto',
                'maps_url' => 'https://maps.google.com/?q=Purwokerto', // <-- Tambahkan ini
                'whatsapp_number' => '6281234567890',
                'opening_hours' => '08:00 - 20:00',
                'is_active' => true
            ]
        );
        Branch::firstOrCreate(
            ['name' => 'Cabang Sokaraja'],
            [
                'address' => 'Jl. Suparjo Rustam, Sokaraja',
                'maps_url' => 'https://maps.google.com/?q=Sokaraja', // <-- Tambahkan ini
                'whatsapp_number' => '6289876543210',
                'opening_hours' => '09:00 - 17:00',
                'is_active' => true
            ]
        );
    }
}