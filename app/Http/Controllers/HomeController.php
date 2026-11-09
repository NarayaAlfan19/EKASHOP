<?php

namespace App\Http\Controllers;

use App\Models\Flower;
use App\Models\Category;
use App\Models\Occasion;
use App\Models\Branch;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data untuk filter di halaman depan
        $categories = Category::all();
        $occasions = Occasion::all();
        $branches = Branch::where('is_active', true)->get();

        // Query produk bunga dengan relasi
        $query = Flower::with(['primaryImage', 'category', 'occasions'])
            ->where('status', 'active');

        // Filter berdasarkan pencarian nama
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
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

        $flowers = $query->latest()->paginate(12)->withQueryString();

        return view('welcome', compact('flowers', 'categories', 'occasions', 'branches'));
    }

    public function show($slug)
    {
        $flower = Flower::with(['images', 'category', 'occasions', 'colors', 'flowerTypes', 'tags', 'branches'])
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedFlowers = Flower::with(['primaryImage', 'category'])
            ->where('category_id', $flower->category_id)
            ->where('id', '!=', $flower->id)
            ->take(4)
            ->get();

        return view('flower-detail', compact('flower', 'relatedFlowers'));
    }
}