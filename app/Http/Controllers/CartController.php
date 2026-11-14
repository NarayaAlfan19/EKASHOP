<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flower;
use App\Models\FlowerVariant;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function update(Request $request)
    {
        $flowerId = $request->flower_id;
        $variantId = $request->variant_id ?? null;
        $quantity = (int) ($request->quantity ?? 1);

        $flower = Flower::with('primaryImage')->findOrFail($flowerId);

        // Default harga dan gambar utama produk
        $price = $flower->discount_price ?? $flower->price;
        $image = $flower->primaryImage->path ?? null;
        $variantName = null;

        // Jika user memilih varian (misal Varian A, B, C)
        if ($variantId) {
            $variant = FlowerVariant::where('flower_id', $flowerId)->find($variantId);
            if ($variant) {
                $price = $variant->price;
                $image = $variant->image ?? $image; // Pakai gambar varian jika ada, jika tidak pakai gambar utama
                $variantName = $variant->name;
            }
        }

        // Buat kunci unik (cart key) agar produk dengan varian berbeda tersimpan terpisah
        $cartKey = $flowerId . ($variantId ? '-' . $variantId : '');

        $cart = session()->get('cart', []);

        if(isset($cart[$cartKey])) {
            // Jika item dengan varian yang sama sudah ada, tambahkan quantity-nya
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            // Jika belum ada, masukkan sebagai item baru di keranjang
            $cart[$cartKey] = [
                "flower_id" => $flower->id,
                "variant_id" => $variantId,
                "name" => $flower->name . ($variantName ? ' (Varian ' . $variantName . ')' : ''),
                "quantity" => $quantity,
                "price" => $price,
                "image" => $image,
                "slug" => $flower->slug
            ];
        }

        session()->put('cart', $cart);

        // Tetap di halaman saat ini dan berikan pesan sukses
        return redirect()->back()->with('success', 'Bunga berhasil ditambahkan ke keranjang.');
    }

    public function destroy($id)
    {
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}