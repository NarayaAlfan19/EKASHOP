<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flower;

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
        $quantity = $request->quantity ?? 1;
        $flower = Flower::with('primaryImage')->findOrFail($flowerId);

        $cart = session()->get('cart', []);

        if(isset($cart[$flowerId])) {
            $cart[$flowerId]['quantity'] += $quantity;
        } else {
            $cart[$flowerId] = [
                "name" => $flower->name,
                "quantity" => $quantity,
                "price" => $flower->discount_price ?? $flower->price,
                "image" => $flower->primaryImage->path ?? null,
                "slug" => $flower->slug
            ];
        }

        session()->put('cart', $cart);

        // Tetap di halaman saat ini dan berikan pesan sukses
        return redirect()->back()->with('success', 'Bunga berhasil ditambahkan ke keranjang.');
    }

    public function destroy($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}