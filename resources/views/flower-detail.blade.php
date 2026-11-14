@php
    $primaryImgUrl = $flower->primaryImage ? asset('storage/' . $flower->primaryImage->path) : '';
    $firstVariant = $flower->variants->first();
    
    // Tentukan gambar awal (jika varian pertama punya gambar, pakai itu. Jika tidak, pakai gambar utama)
    $initialImg = ($firstVariant && $firstVariant->image) ? asset('storage/' . $firstVariant->image) : $primaryImgUrl;
    $initialPrice = number_format($firstVariant ? $firstVariant->price : ($flower->discount_price ?? $flower->price), 0, ',', '.');
    $initialId = $firstVariant ? $firstVariant->id : '';
@endphp

<!DOCTYPE html>
<html lang="id" x-data="{ 
    isOpen: false, 
    activeImage: '{{ $initialImg }}',
    currentPrice: '{{ $initialPrice }}',
    selectedVariant: '{{ $initialId }}',
    selectVariant(price, image, id) {
        this.currentPrice = new Intl.NumberFormat('id-ID').format(price);
        this.activeImage = image;
        this.selectedVariant = id;
    }
}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $flower->meta_title ?? $flower->name }} - EKASHOP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#0A0A0A] text-[#F5F0E6] font-sans antialiased selection:bg-[#C9A24B] selection:text-black">

    <!-- Navbar -->
    <header class="bg-[#141414] border-b border-[#262626] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-3xl font-serif tracking-widest bg-gradient-to-r from-[#C9A24B] to-[#E3C16F] bg-clip-text text-transparent">EKASHOP</a>
            
            <div class="flex items-center space-x-6">
                <a href="{{ route('home') }}" class="text-[#A8A29E] hover:text-[#C9A24B] font-medium transition">← Kembali ke Katalog</a>

                <!-- Ikon Keranjang di Navbar Detail Produk -->
                <a href="{{ route('cart.index') }}" class="relative p-2 text-[#A8A29E] hover:text-[#C9A24B] transition duration-300 group flex items-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    
                    @php
                        $cartCount = collect(session()->get('cart', []))->sum('quantity');
                    @endphp

                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 h-5 w-5 bg-[#C9A24B] text-black text-[10px] font-bold flex items-center justify-center rounded-full shadow-md">
                            {{ $cartCount }}
                        </span>
                    @else
                        <span class="absolute top-0 right-0 h-4 w-4 bg-[#C9A24B] text-black text-[10px] font-bold flex items-center justify-center rounded-full transform scale-0 group-hover:scale-100 transition-transform">0</span>
                    @endif
                </a>
            </div>
        </div>
    </header>

    <!-- Notifikasi Flash Message -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-emerald-950/80 border border-emerald-500 text-emerald-200 p-4 rounded-xl text-sm flex items-center justify-between shadow-lg">
                <span>{{ session('success') }}</span>
                <a href="{{ route('cart.index') }}" class="underline font-bold hover:text-white transition">Lihat Keranjang →</a>
            </div>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-[#141414] rounded-2xl shadow-xl border border-[#262626] overflow-hidden grid grid-cols-1 md:grid-cols-2 gap-8 p-8">
            
            <!-- Galeri Foto (Dinamis Berubah Sesuai Varian yang Dipilih) -->
            <div>
                <div class="h-96 bg-[#0A0A0A] rounded-xl overflow-hidden mb-4 border border-[#262626] flex items-center justify-center relative group">
                    <!-- Tampilkan gambar jika activeImage memiliki isi path URL -->
                    <template x-if="activeImage">
                        <img :src="activeImage" 
                             alt="{{ $flower->name }}" 
                             class="w-full h-full object-cover cursor-pointer hover:scale-105 transition duration-300"
                             @click="isOpen = true">
                    </template>

                    <template x-if="!activeImage">
                        <div class="flex items-center justify-center h-full text-[#A8A29E]/50">No Image Available</div>
                    </template>
                    
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center pointer-events-none text-xs text-[#C9A24B] font-semibold tracking-wider uppercase" x-show="activeImage">
                        Klik untuk memperbesar
                    </div>
                </div>
            </div>

            <!-- Informasi Produk -->
            <div>
                <span class="text-xs uppercase tracking-wider text-[#C9A24B] font-semibold bg-[#0A0A0A] border border-[#C9A24B]/30 px-3 py-1 rounded-full">{{ $flower->category->name }}</span>
                <h1 class="text-3xl font-serif text-[#F5F0E6] mt-3">{{ $flower->name }}</h1>
                
                <!-- Harga Dinamis Berdasarkan Varian -->
                <div class="mt-4 flex items-baseline gap-3">
                    <span class="text-2xl font-bold text-[#C9A24B]">Rp <span x-text="currentPrice"></span></span>
                    @if($flower->discount_price && $flower->variants->count() === 0)
                        <span class="text-[#A8A29E]/60 line-through text-lg">Rp {{ number_format($flower->price, 0, ',', '.') }}</span>
                    @endif
                </div>

                <p class="text-[#A8A29E] mt-6 leading-relaxed">{{ $flower->description }}</p>

                <!-- Pilihan Varian (Model A, B, C, dll) -->
                @if($flower->variants->count() > 0)
                    <div class="mt-6 border-t border-[#262626] pt-6">
                        <label class="block text-xs font-semibold text-[#A8A29E] mb-3 uppercase tracking-widest">
                            Pilih Varian / Model:
                        </label>
                        <div class="flex flex-wrap gap-3">
                            @foreach($flower->variants as $variant)
                                @php
                                    // Fallback: Jika varian punya foto, pakai foto varian. Jika kosong, pakai foto utama produk.
                                    $variantImgUrl = $variant->image ? asset('storage/' . $variant->image) : $primaryImgUrl;
                                @endphp
                                <button type="button" 
                                    @click="selectVariant({{ $variant->price }}, '{{ $variantImgUrl }}', '{{ $variant->id }}')"
                                    :class="selectedVariant == '{{ $variant->id }}' ? 'bg-[#C9A24B] text-black font-bold shadow-[0_0_15px_rgba(201,162,75,0.3)]' : 'bg-[#0A0A0A] text-[#F5F0E6] border border-[#262626] hover:border-[#C9A24B]'"
                                    class="px-4 py-2 rounded-lg text-sm transition duration-200">
                                    {{ $variant->name }} (Rp {{ number_format($variant->price, 0, ',', '.') }})
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mt-6 border-t border-[#262626] pt-6 space-y-3">
                    @if($flower->size)
                        <div class="flex items-center text-sm">
                            <span class="font-semibold w-32 text-[#A8A29E]">Ukuran:</span>
                            <span class="text-[#F5F0E6]">{{ $flower->size }}</span>
                        </div>
                    @endif

                    <div class="flex items-center text-sm">
                        <span class="font-semibold w-32 text-[#A8A29E]">Acara (Occasion):</span>
                        <span class="text-[#F5F0E6]">
                            @foreach($flower->occasions as $occ)
                                <span class="bg-[#0A0A0A] border border-[#262626] text-[#F5F0E6] text-xs px-2 py-1 rounded mr-1">{{ $occ->name }}</span>
                            @endforeach
                        </span>
                    </div>

                    <div class="flex items-center text-sm">
                        <span class="font-semibold w-32 text-[#A8A29E]">Warna:</span>
                        <span class="text-[#F5F0E6]">
                            @foreach($flower->colors as $col)
                                <span class="inline-block w-3 h-3 rounded-full mr-1" style="background-color: {{ $col->hex }}; border: 1px solid #555;"></span> {{ $col->name }}
                            @endforeach
                        </span>
                    </div>
                </div>

                <!-- Tombol Tambah ke Keranjang -->
                <div class="mt-8">
                    <form action="{{ route('cart.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="flower_id" value="{{ $flower->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <!-- Kirim ID Varian yang dipilih user -->
                        <input type="hidden" name="variant_id" x-model="selectedVariant">
                        
                        <button type="submit" class="w-full bg-[#C9A24B] hover:bg-[#E3C16F] text-black font-bold py-3.5 px-6 rounded-xl shadow-[0_0_15px_rgba(201,162,75,0.2)] transition duration-300 flex items-center justify-center gap-2 uppercase tracking-wider text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Tambah ke Keranjang
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <!-- Produk Terkait -->
        @if($relatedFlowers->count() > 0)
            <div class="mt-16">
                <h3 class="text-2xl font-serif text-[#F5F0E6] mb-6">Produk Terkait</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($relatedFlowers as $rel)
                        <div class="bg-[#141414] rounded-xl shadow-md hover:shadow-[0_0_25px_rgba(201,162,75,0.05)] transition overflow-hidden border border-[#262626] flex flex-col justify-between">
                            <div class="h-48 bg-[#0A0A0A]">
                                @if($rel->primaryImage)
                                    <img src="{{ asset('storage/' . $rel->primaryImage->path) }}" alt="{{ $rel->name }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="p-4">
                                <h4 class="font-serif text-lg text-[#F5F0E6] line-clamp-1">{{ $rel->name }}</h4>
                                <span class="text-[#C9A24B] font-bold text-sm mt-1 block">Rp {{ number_format($rel->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="p-4 pt-0">
                                <a href="{{ route('flower.show', $rel->slug) }}" class="block w-full text-center bg-[#0A0A0A] hover:bg-[#C9A24B] hover:text-black text-[#C9A24B] font-semibold py-2 rounded-lg border border-[#C9A24B]/30 text-sm transition">Lihat</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </main>

    <!-- MODAL LIGHTBOX FULLSCREEN -->
    <div x-show="isOpen" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 p-4 backdrop-blur-sm"
         @click.self="isOpen = false"
         @keydown.escape.window="isOpen = false">
        
        <div class="relative max-w-5xl w-full max-h-[90vh] flex items-center justify-center">
            <!-- Tombol Close (X) -->
            <button @click="isOpen = false" class="absolute -top-12 right-0 text-[#F5F0E6] hover:text-[#C9A24B] text-4xl font-light transition">×</button>
            
            <!-- Gambar Full Size -->
            <img :src="activeImage" class="max-w-full max-h-[85vh] object-contain rounded-xl border border-[#C9A24B]/30 shadow-2xl">
        </div>
    </div>

</body>
</html>