<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - EKASHOP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0A0A0A] text-[#F5F0E6] font-sans antialiased selection:bg-[#C9A24B] selection:text-black">

    <!-- Navbar -->
    <header class="bg-[#141414] border-b border-[#262626] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-3xl font-serif tracking-widest bg-gradient-to-r from-[#C9A24B] to-[#E3C16F] bg-clip-text text-transparent">EKASHOP</a>
            <a href="{{ route('home') }}" class="text-[#A8A29E] hover:text-[#C9A24B] font-medium transition">&larr; Lanjut Belanja</a>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-serif text-[#F5F0E6] mb-8">Keranjang Belanja Anda</h1>

        @if(session('success'))
            <div class="bg-emerald-950/80 border border-emerald-500 text-emerald-200 p-4 rounded-xl mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(empty($cart))
            <div class="bg-[#141414] border border-[#262626] rounded-2xl p-12 text-center">
                <p class="text-[#A8A29E] text-lg mb-6">Keranjang belanja Anda masih kosong.</p>
                <a href="{{ route('home') }}" class="inline-block bg-[#C9A24B] text-black font-bold px-6 py-3 rounded-xl hover:bg-[#E3C16F] transition text-sm">
                    Pilih Bunga Sekarang
                </a>
            </div>
        @else
            <div class="bg-[#141414] border border-[#262626] rounded-2xl overflow-hidden shadow-xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#262626] text-xs text-[#A8A29E] uppercase tracking-wider bg-[#0A0A0A]">
                            <th class="p-4">Produk</th>
                            <th class="p-4">Harga</th>
                            <th class="p-4 text-center">Jumlah</th>
                            <th class="p-4">Subtotal</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#262626] text-sm text-[#F5F0E6]">
                        @php $total = 0; @endphp
                        @foreach($cart as $id => $item)
                            @php 
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;
                            @endphp
                            <tr class="hover:bg-[#1a1a1a] transition">
                                <td class="p-4 flex items-center gap-4">
                                    @if(!empty($item['image']))
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded-lg border border-[#262626]">
                                    @else
                                        <div class="w-16 h-16 bg-[#0A0A0A] rounded-lg flex items-center justify-center text-xs text-[#A8A29E]">No Image</div>
                                    @endif
                                    <span class="font-serif font-semibold text-lg text-[#F5F0E6]">{{ $item['name'] }}</span>
                                </td>
                                <td class="p-4 text-[#C9A24B]">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                <td class="p-4 text-center">{{ $item['quantity'] }}</td>
                                <td class="p-4 font-bold text-[#C9A24B]">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                <td class="p-4 text-center">
                                    <form action="{{ route('cart.destroy', $id) }}" method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 font-semibold text-xs transition">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="p-6 bg-[#0A0A0A] border-t border-[#262626] flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-lg font-serif">
                        Total Keseluruhan: <span class="text-[#C9A24B] font-bold text-2xl ml-2">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <a href="#" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-8 py-3.5 rounded-xl shadow-lg transition uppercase tracking-wider text-sm flex items-center gap-2">
                        Pesan via WhatsApp
                    </a>
                </div>
            </div>
        @endif
    </main>

</body>
</html>