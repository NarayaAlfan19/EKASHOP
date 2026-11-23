<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flower;
use App\Models\FlowerImage;
use App\Models\FlowerVariant;
use App\Models\Category;
use App\Models\Occasion;
use App\Models\Color;
use App\Models\FlowerType;
use App\Models\Tag;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FlowerController extends Controller
{
    public function index()
    {
        $flowers = Flower::with(['category', 'variants'])->latest()->paginate(15);
        return view('admin.flowers.index', compact('flowers'));
    }

    public function create()
    {
        $categories = Category::all();
        $occasions = Occasion::all();
        $colors = Color::all();
        $types = FlowerType::all();
        $tags = Tag::all();

        return view('admin.flowers.create', compact('categories', 'occasions', 'colors', 'types', 'tags'));
    }

    public function store(Request $request, ImageUploadService $imageService)
    {
        $this->sanitizePrices($request);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:1',
            'discount_price' => 'nullable|integer|lt:price',
            'description' => 'required|string',
            'size' => 'nullable|string',
            'status' => 'required|in:draft,active,sold_out',
            'is_featured' => 'boolean',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            // Validasi Varian (Gambar opsional)
            'variants' => 'nullable|array',
            'variants.*.name' => 'required|string|max:50',
            'variants.*.price' => 'required|integer|min:1',
            'variants.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $slug = Str::slug($validated['name']);
            $baseSlug = $slug;
            $counter = 1;
            while (Flower::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            $flower = Flower::create([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => $slug,
                'description' => $validated['description'],
                'price' => $validated['price'],
                'discount_price' => $validated['discount_price'] ?? null,
                'size' => $validated['size'] ?? null,
                'status' => $validated['status'],
                'is_featured' => $request->has('is_featured'),
            ]);

            // Simpan Gambar Utama Produk
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $file) {
                    $path = $imageService->uploadAndProcess($file);
                    FlowerImage::create([
                        'flower_id' => $flower->id,
                        'path' => $path,
                        'is_primary' => $index === 0,
                        'sort_order' => $index + 1
                    ]);
                }
            }

            // Simpan Varian Produk (Nama, Harga, & Gambar Opsional)
            if ($request->has('variants')) {
                foreach ($request->variants as $index => $varData) {
                    $variantImagePath = null;
                    if ($request->hasFile("variants.$index.image")) {
                        $variantImagePath = $imageService->uploadAndProcess($request->file("variants.$index.image"));
                    }

                    FlowerVariant::create([
                        'flower_id' => $flower->id,
                        'name' => $varData['name'],
                        'price' => $varData['price'],
                        'image' => $variantImagePath,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.bunga.index')->with('success', 'Bunga berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $flower = Flower::with(['images', 'variants'])->findOrFail($id);
        $categories = Category::all();
        $occasions = Occasion::all();
        $colors = Color::all();
        $types = FlowerType::all();
        $tags = Tag::all();

        return view('admin.flowers.edit', compact('flower', 'categories', 'occasions', 'colors', 'types', 'tags'));
    }

    public function update(Request $request, $id, ImageUploadService $imageService)
    {
        $flower = Flower::findOrFail($id);
        $this->sanitizePrices($request);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:1',
            'discount_price' => 'nullable|integer|lt:price',
            'description' => 'required|string',
            'size' => 'nullable|string',
            'status' => 'required|in:draft,active,sold_out',
            'is_featured' => 'boolean',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            // Validasi Varian saat update (Gambar opsional)
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:flower_variants,id',
            'variants.*.name' => 'required|string|max:50',
            'variants.*.price' => 'required|integer|min:1',
            'variants.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $flower->update([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'discount_price' => $validated['discount_price'] ?? null,
                'size' => $validated['size'] ?? null,
                'status' => $validated['status'],
                'is_featured' => $request->has('is_featured'),
            ]);

            // Ganti Foto Utama Baru (jika di-upload)
            if ($request->hasFile('images')) {
                $flower->images()->update(['is_primary' => false]);

                foreach ($request->file('images') as $index => $file) {
                    $path = $imageService->uploadAndProcess($file);
                    FlowerImage::create([
                        'flower_id' => $flower->id,
                        'path' => $path,
                        'is_primary' => $index === 0,
                        'sort_order' => $flower->images()->count() + 1
                    ]);
                }
            }

            // Sinkronisasi Varian (Update/Create + Gambar Opsional)
            $submittedVariantIds = [];
            if ($request->has('variants')) {
                foreach ($request->variants as $index => $varData) {
                    $variantImagePath = null;
                    if ($request->hasFile("variants.$index.image")) {
                        $variantImagePath = $imageService->uploadAndProcess($request->file("variants.$index.image"));
                    }

                    if (!empty($varData['id'])) {
                        $variant = FlowerVariant::where('flower_id', $flower->id)->find($varData['id']);
                        if ($variant) {
                            $updateData = [
                                'name' => $varData['name'],
                                'price' => $varData['price'],
                            ];
                            // Hanya timpa gambar jika ada gambar baru yang di-upload
                            if ($variantImagePath) {
                                $updateData['image'] = $variantImagePath;
                            }
                            $variant->update($updateData);
                            $submittedVariantIds[] = $variant->id;
                        }
                    } else {
                        $newVariant = FlowerVariant::create([
                            'flower_id' => $flower->id,
                            'name' => $varData['name'],
                            'price' => $varData['price'],
                            'image' => $variantImagePath,
                        ]);
                        $submittedVariantIds[] = $newVariant->id;
                    }
                }
            }

            // Hapus varian yang dihapus oleh admin di form
            $flower->variants()->whereNotIn('id', $submittedVariantIds)->delete();

            DB::commit();
            return redirect()->route('admin.bunga.index')->with('success', 'Bunga berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $flower = Flower::with(['images', 'variants'])->findOrFail($id);

            foreach ($flower->images as $img) {
                $cleanPath = str_replace(['public/', 'storage/'], '', $img->path);
                if ($cleanPath && Storage::disk('public')->exists($cleanPath)) {
                    Storage::disk('public')->delete($cleanPath);
                }
            }

            foreach ($flower->variants as $variant) {
                if ($variant->image) {
                    $cleanVarPath = str_replace(['public/', 'storage/'], '', $variant->image);
                    if (Storage::disk('public')->exists($cleanVarPath)) {
                        Storage::disk('public')->delete($cleanVarPath);
                    }
                }
            }

            $flower->delete();

            DB::commit();
            return redirect()->route('admin.bunga.index')->with('success', 'Bunga berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Gagal menghapus bunga: ' . $e->getMessage());
        }
    }

    private function sanitizePrices(Request $request): void
    {
        $data = [];

        if ($request->has('price')) {
            $data['price'] = $this->normalizeRupiah($request->input('price'));
        }

        if ($request->has('discount_price')) {
            $data['discount_price'] = $request->filled('discount_price')
                ? $this->normalizeRupiah($request->input('discount_price'))
                : null;
        }

        if ($request->has('variants') && is_array($request->input('variants'))) {
            $data['variants'] = collect($request->input('variants'))->map(function ($variant) {
                if (is_array($variant) && array_key_exists('price', $variant)) {
                    $variant['price'] = $this->normalizeRupiah($variant['price']);
                }
                return $variant;
            })->toArray();
        }

        if (!empty($data)) {
            $request->merge($data);
        }
    }

    private function normalizeRupiah($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_int($value) || is_float($value)) {
            return (int) round($value);
        }

        $str = trim((string) $value);

        // Buang bagian desimal jika ada koma (misal: "50000,00" atau "50.000,00")
        if (str_contains($str, ',')) {
            $str = explode(',', $str)[0];
        } elseif (preg_match('/\.\d{1,2}$/', $str)) {
            // Buang bagian desimal jika format titik decimal (misal: "50000.00")
            $str = preg_replace('/\.\d{1,2}$/', '', $str);
        }

        $digitsOnly = preg_replace('/\D/', '', $str);

        return $digitsOnly !== '' ? (int) $digitsOnly : null;
    }
}