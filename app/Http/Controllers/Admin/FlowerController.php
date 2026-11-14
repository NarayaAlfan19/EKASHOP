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
            // Validasi Varian (Tanpa Gambar)
            'variants' => 'nullable|array',
            'variants.*.name' => 'required|string|max:50',
            'variants.*.price' => 'required|integer|min:1',
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

            // Simpan Varian Produk (Nama & Harga saja)
            if ($request->has('variants')) {
                foreach ($request->variants as $varData) {
                    FlowerVariant::create([
                        'flower_id' => $flower->id,
                        'name' => $varData['name'],
                        'price' => $varData['price'],
                        'image' => null, // Dikosongkan karena tidak pakai foto varian
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
            // Validasi Varian saat update (Tanpa Gambar)
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:flower_variants,id',
            'variants.*.name' => 'required|string|max:50',
            'variants.*.price' => 'required|integer|min:1',
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

            // Tambah Gambar Utama Baru (jika di-upload)
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

            // Sinkronisasi Varian (Nama & Harga saja)
            $submittedVariantIds = [];
            if ($request->has('variants')) {
                foreach ($request->variants as $varData) {
                    if (isset($varData['id']) && $varData['id']) {
                        $variant = FlowerVariant::where('flower_id', $flower->id)->find($varData['id']);
                        if ($variant) {
                            $variant->update([
                                'name' => $varData['name'],
                                'price' => $varData['price'],
                            ]);
                            $submittedVariantIds[] = $variant->id;
                        }
                    } else {
                        $newVariant = FlowerVariant::create([
                            'flower_id' => $flower->id,
                            'name' => $varData['name'],
                            'price' => $varData['price'],
                            'image' => null,
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
}