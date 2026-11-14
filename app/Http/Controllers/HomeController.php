<?php

namespace App\Http\Controllers;

use App\Models\Flower;
use App\Models\Category;
use App\Models\Occasion;
use App\Models\Color;
use App\Models\Branch;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data untuk filter di halaman depan
        $categories = Category::all();
        $occasions = Occasion::all();
        $colors = Color::all(); 
        $branches = Branch::where('is_active', true)->get();

        // Kueri produk bunga: tampilkan active DAN sold_out (draft disembunyikan)
        $query = Flower::with(['primaryImage', 'category', 'occasions'])
            ->whereIn('status', ['active', 'sold_out']);

        // Filter berdasarkan pencarian nama, deskripsi, dan tag
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('tags', function ($t) use ($search) {
                      $t->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter berdasarkan occasion (acara)
        if ($request->filled('occasion')) {
            $query->whereHas('occasions', function ($q) use ($request) {
                $q->where('slug', $request->occasion);
            });
        }

        // Filter berdasarkan warna
        if ($request->filled('color')) {
            $query->whereHas('colors', function ($q) use ($request) {
                $q->where('id', $request->color);
            });
        }

        // Filter ketersediaan cabang (hanya tampilkan yang is_available = true)
        if ($request->filled('branch')) {
            $query->whereHas('branches', function ($q) use ($request) {
                $q->where('branches.id', $request->branch)
                  ->where('branch_flower.is_available', true);
            });
        }

        // Pengurutan (Terbaru, Termurah, Termahal)
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'termurah':
                    $query->orderByRaw('COALESCE(discount_price, price) ASC');
                    break;
                case 'termahal':
                    $query->orderByRaw('COALESCE(discount_price, price) DESC');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $flowers = $query->paginate(12)->withQueryString();

        return view('welcome', compact('flowers', 'categories', 'occasions', 'colors', 'branches'));
    }

    public function show($slug)
    {
        $flower = Flower::with([
                'images', 
                'category', 
                'occasions', 
                'colors', 
                'types', 
                'tags', 
                'variants', // <-- DITAMBAHKAN AGAR VARIAN BISA DIAKSES DI HALAMAN DETAIL
                'branches' => function($q) {
                    $q->where('is_active', true);
                }
            ])
            ->where('slug', $slug)
            ->where('status', '!=', 'draft') 
            ->firstOrFail();

        // Fitur Bunga Serupa
        $relatedFlowers = Flower::with(['primaryImage', 'category'])
            ->where('category_id', $flower->category_id)
            ->where('id', '!=', $flower->id)
            ->whereIn('status', ['active', 'sold_out'])
            ->take(4)
            ->get();

        return view('flower-detail', compact('flower', 'relatedFlowers'));
    }
}