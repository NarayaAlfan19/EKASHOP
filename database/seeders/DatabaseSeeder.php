<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Occasion;
use App\Models\Color;
use App\Models\FlowerType;
use App\Models\Tag;
use App\Models\Branch;
use App\Models\Flower;
use App\Models\FlowerImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin Default
        User::updateOrCreate(
            ['email' => 'admin@ekashop.com'],
            [
                'name' => 'Admin EKASHOP',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // 2. Buat Pengaturan (Settings) Website
        $settings = [
            ['key' => 'store_name', 'value' => 'EKASHOP Florist & Gift'],
            ['key' => 'main_whatsapp', 'value' => '6281234567890'],
            ['key' => 'min_lead_time_hours', 'value' => '3'],
            ['key' => 'hero_title', 'value' => 'Kirimkan Kebahagiaan Lewat Rangkaian Bunga Indah'],
            ['key' => 'hero_subtitle', 'value' => 'Pesan buket bunga segar berkualitas untuk setiap momen spesial Anda.'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], ['value' => $setting['value']]);
        }

        // 3. Master Data: Categories
        $categories = [
            ['name' => 'Buket Bunga (Hand Bouquet)', 'slug' => 'buket-bunga'],
            ['name' => 'Standing Flower', 'slug' => 'standing-flower'],
            ['name' => 'Bloom Box', 'slug' => 'bloom-box'],
            ['name' => 'Bunga Meja (Table Flower)', 'slug' => 'bunga-meja'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 4. Master Data: Occasions (Acara)
        $occasions = [
            ['name' => 'Wedding / Pernikahan', 'slug' => 'wedding'],
            ['name' => 'Wisuda (Graduation)', 'slug' => 'wisuda'],
            ['name' => 'Ulang Tahun (Birthday)', 'slug' => 'ulang-tahun'],
            ['name' => 'Duka Cita', 'slug' => 'duka-cita'],
            ['name' => 'Valentine', 'slug' => 'valentine'],
        ];

        foreach ($occasions as $occ) {
            Occasion::updateOrCreate(['slug' => $occ['slug']], $occ);
        }

        // 5. Master Data: Colors
        $colors = [
            ['name' => 'Merah (Red)', 'hex' => '#EF4444'],
            ['name' => 'Merah Muda (Pink)', 'hex' => '#EC4899'],
            ['name' => 'Putih (White)', 'hex' => '#FFFFFF'],
            ['name' => 'Kuning (Yellow)', 'hex' => '#EAB308'],
            ['name' => 'Ungu (Purple)', 'hex' => '#A855F7'],
        ];

        foreach ($colors as $col) {
            Color::updateOrCreate(['name' => $col['name']], $col);
        }

        // 6. Master Data: Flower Types (Jenis Bunga)
        $flowerTypes = [
            ['name' => 'Mawar (Rose)', 'slug' => 'mawar'],
            ['name' => 'Lily', 'slug' => 'lily'],
            ['name' => 'Tulip', 'slug' => 'tulip'],
            ['name' => 'Anggrek (Orchid)', 'slug' => 'anggrek'],
        ];

        foreach ($flowerTypes as $ft) {
            FlowerType::updateOrCreate(['slug' => $ft['slug']], $ft);
        }

        // 7. Master Data: Tags
        $tags = [
            ['name' => 'Best Seller', 'slug' => 'best-seller', 'type' => 'keyword'],
            ['name' => 'Romantis', 'slug' => 'romantis', 'type' => 'mood'],
            ['name' => 'Elegan', 'slug' => 'elegan', 'type' => 'mood'],
            ['name' => 'Fresh Daily', 'slug' => 'fresh-daily', 'type' => 'keyword'],
        ];

        foreach ($tags as $tag) {
            Tag::updateOrCreate(['slug' => $tag['slug']], $tag);
        }

        // 8. Master Data: Branches (Cabang Toko)
        $branches = [
            [
                'name' => 'EKASHOP Pusat Purwokerto',
                'address' => 'Jl. Jenderal Soedirman No. 123, Purwokerto',
                'maps_url' => 'https://maps.google.com/?q=Purwokerto',
                'whatsapp_number' => '6281122334455',
                'opening_hours' => '08:00 - 20:00 WIB',
                'is_active' => true,
            ],
            [
                'name' => 'EKASHOP Cabang Sokaraja',
                'address' => 'Jl. HR Boenyamin No. 45, Sokaraja',
                'maps_url' => 'https://maps.google.com/?q=Sokaraja',
                'whatsapp_number' => '6281122334466',
                'opening_hours' => '09:00 - 18:00 WIB',
                'is_active' => true,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::updateOrCreate(['name' => $branch['name']], $branch);
        }

        // 9. Dummy Produk Bunga (Flowers)
        $categoryBuket = Category::where('slug', 'buket-bunga')->first();
        $categoryBox = Category::where('slug', 'bloom-box')->first();

        $flowersData = [
            [
                'category_id' => $categoryBuket->id,
                'name' => 'Ruby Red Rose Bouquet',
                'slug' => 'ruby-red-rose-bouquet',
                'description' => 'Rangkaian 10 tangkai mawar merah segar pilihan dengan balutan kertas premium mewah.',
                'price' => 250000,
                'discount_price' => 220000,
                'size' => 'Medium (40x50 cm)',
                'status' => 'active',
                'is_featured' => true,
                'meta_title' => 'Buket Mawar Merah Mewah - Ruby Red Bouquet',
                'meta_description' => 'Beli buket mawar merah segar berkualitas untuk hadiah spesial pasangan Anda.',
                'images' => [
                    ['path' => 'flowers/ruby-red-1.jpg', 'is_primary' => true, 'sort_order' => 1],
                    ['path' => 'flowers/ruby-red-2.jpg', 'is_primary' => false, 'sort_order' => 2],
                ],
                'occasions' => ['valentine', 'ulang-tahun', 'wedding'],
                'colors' => ['Merah (Red)'],
                'flower_types' => ['Mawar (Rose)'],
                'tags' => ['best-seller', 'romantis'],
                'branches' => [1, 2], // Tersedia di cabang 1 dan 2
            ],
            [
                'category_id' => $categoryBox->id,
                'name' => 'Sweet Pink Bloom Box',
                'slug' => 'sweet-pink-bloom-box',
                'description' => 'Kombinasi bunga mawar pink dan lily dalam kotak elegan, cocok untuk wisuda atau anniversary.',
                'price' => 350000,
                'discount_price' => null,
                'size' => 'Large (Box 25cm)',
                'status' => 'active',
                'is_featured' => true,
                'meta_title' => 'Bloom Box Bunga Pink Elegan - EKASHOP',
                'meta_description' => 'Kado bloom box cantik dan segar untuk momen wisuda dan ulang tahun.',
                'images' => [
                    ['path' => 'flowers/pink-box-1.jpg', 'is_primary' => true, 'sort_order' => 1],
                ],
                'occasions' => ['wisuda', 'ulang-tahun'],
                'colors' => ['Merah Muda (Pink)', 'Putih (White)'],
                'flower_types' => ['Mawar (Rose)', 'Lily'],
                'tags' => ['elegan', 'fresh-daily'],
                'branches' => [1], // Hanya di cabang 1
            ],
        ];

        foreach ($flowersData as $data) {
            $images = $data['images'];
            $occasions = $data['occasions'];
            $colors = $data['colors'];
            $flowerTypes = $data['flower_types'];
            $tags = $data['tags'];
            $branchIds = $data['branches'];

            unset($data['images'], $data['occasions'], $data['colors'], $data['flower_types'], $data['tags'], $data['branches']);

            $flower = Flower::updateOrCreate(['slug' => $data['slug']], $data);

            // Seed Images
            foreach ($images as $img) {
                FlowerImage::updateOrCreate(
                    ['flower_id' => $flower->id, 'path' => $img['path']],
                    $img
                );
            }

            // Sync Pivot Relations
            $flower->occasions()->sync(Occasion::whereIn('slug', $occasions)->pluck('id'));
            $flower->colors()->sync(Color::whereIn('name', $colors)->pluck('id'));
            $flower->flowerTypes()->sync(FlowerType::whereIn('slug', $flowerTypes)->pluck('id'));
            $flower->tags()->sync(Tag::whereIn('slug', $tags)->pluck('id'));
            
            // Sync Branch Availability
            $branchData = [];
            foreach ($branchIds as $bId) {
                $branchData[$bId] = ['is_available' => true];
            }
            $flower->branches()->sync($branchData);
        }
    }
}