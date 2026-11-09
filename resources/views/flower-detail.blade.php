<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $flower->meta_title ?? $flower->name }} - EKASHOP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-zinc-950 text-zinc-100 antialiased">

    <!-- Navbar -->
    <header class="bg-zinc-900 border-b border-zinc-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-amber-400 tracking-wider">EKASHOP</a>
            <a href="{{ route('home') }}" class="text-zinc-300 hover:text-amber-400 font-medium transition">&larr; Kembali ke Katalog</a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-zinc-900 rounded-2xl shadow-xl border border-zinc-800 overflow-hidden grid grid-cols-1 md:grid-cols-2 gap-8 p-8">
            
            <!-- Galeri Foto -->
            <div>
                <div class="h-96 bg-zinc-950 rounded-xl overflow-hidden mb-4 border border-zinc-800">
                    @if($flower->primaryImage)
                        <img src="{{ asset('storage/' . $flower->primaryImage->path) }}" alt="{{ $flower->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex items-center justify-center h-full text-zinc-600">No Image Available</div>
                    @endif
                </div>
            </div>

            <!-- Informasi Produk -->
            <div>
                <span class="text-xs uppercase tracking-wider text-amber-400 font-semibold bg-zinc-950 border border-amber-500/30 px-3 py-1 rounded-full">{{ $flower->category->name }}</span>
                <h1 class="text-3xl font-extrabold text-zinc-100 mt-3">{{ $flower->name }}</h1>
                
                <div class="mt-4 flex items-baseline gap-3">
                    @if($flower->discount_price)
                        <span class="text-2xl font-bold text-amber-400">Rp {{ number_format($flower->discount_price, 0, ',', '.') }}</span>
                        <span class="text-zinc-500 line-through text-lg">Rp {{ number_format($flower->price, 0, ',', '.') }}</span>
                    @else
                        <span class="text-2xl font-bold text-amber-400">Rp {{ number_format($flower->price, 0, ',', '.') }}</span>
                    @endif
                </div>

                <p class="text-zinc-400 mt-6 leading-relaxed">{{ $flower->description }}</p>

                <div class="mt-6 border-t border-zinc-800 pt-6 space-y-3">
                    @if($flower->size)
                        <div class="flex items-center text-sm">
                            <span class="font-semibold w-32 text-zinc-500">Ukuran:</span>
                            <span class="text-zinc-300">{{ $flower->size }}</span>
                        </div>
                    @endif

                    <div class="flex items-center text-sm">
                        <span class="font-semibold w-32 text-zinc-500">Acara (Occasion):</span>
                        <span class="text-zinc-300">
                            @foreach($flower->occasions as $occ)
                                <span class="bg-zinc-950 border border-zinc-800 text-zinc-300 text-xs px-2 py-1 rounded mr-1">{{ $occ->name }}</span>
                            @endforeach
                        </span>
                    </div>

                    <div class="flex items-center text-sm">
                        <span class="font-semibold w-32 text-zinc-500">Warna:</span>
                        <span class="text-zinc-300">
                            @foreach($flower->colors as $col)
                                <span class="inline-block w-3 h-3 rounded-full mr-1" style="background-color: {{ $col->hex }}; border: 1px solid #555;"></span> {{ $col->name }}
                            @endforeach
                        </span>
                    </div>
                </div>

                <!-- Tombol Aksi WhatsApp Inquiry -->
                <div class="mt-8">
                    <a href="https://wa.me/?text=Halo%20EKASHOP,%20saya%20tertarik%20pesan%20produk%20{{ urlencode($flower->name) }}" target="_blank" class="block w-full text-center bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 rounded-xl shadow-lg transition">
                        Pesan via WhatsApp
                    </a>
                </div>
            </div>

        </div>

        <!-- Produk Terkait -->
        @if($relatedFlowers->count() > 0)
            <div class="mt-16">
                <h3 class="text-2xl font-bold text-zinc-100 mb-6">Produk Terkait</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($relatedFlowers as $rel)
                        <div class="bg-zinc-900 rounded-xl shadow-md hover:shadow-amber-500/10 transition overflow-hidden border border-zinc-800 flex flex-col justify-between">
                            <div class="h-48 bg-zinc-950">
                                @if($rel->primaryImage)
                                    <img src="{{ asset('storage/' . $rel->primaryImage->path) }}" alt="{{ $rel->name }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="p-4">
                                <h4 class="font-bold text-zinc-100 line-clamp-1">{{ $rel->name }}</h4>
                                <span class="text-amber-400 font-bold text-sm mt-1 block">Rp {{ number_format($rel->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="p-4 pt-0">
                                <a href="{{ route('flower.show', $rel->slug) }}" class="block w-full text-center bg-zinc-950 hover:bg-amber-500 hover:text-zinc-950 text-amber-400 font-semibold py-1.5 rounded-lg border border-amber-500/30 text-sm transition">Lihat</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </main>

</body>
</html>