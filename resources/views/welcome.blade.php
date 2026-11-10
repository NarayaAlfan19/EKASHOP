<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog EKASHOP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Memperhalus tampilan scrollbar agar lebih premium */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0A0A0A; }
        ::-webkit-scrollbar-thumb { background: #262626; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #C9A24B; }
    </style>
</head>
<body class="bg-[#0A0A0A] text-[#F5F0E6] font-sans antialiased selection:bg-[#C9A24B] selection:text-black">

    <!-- Navbar -->
    <nav class="border-b border-[#262626]/60 bg-[#0A0A0A]/90 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-20">
            <a href="{{ route('home') }}" class="text-3xl font-serif tracking-widest bg-gradient-to-r from-[#C9A24B] to-[#E3C16F] bg-clip-text text-transparent">
                EKASHOP
            </a>
            <div class="flex items-center space-x-6">
                <a href="#" class="text-sm font-medium text-[#A8A29E] hover:text-[#C9A24B] transition duration-300">Bantu Saya Memilih</a>
                
                <!-- Tombol Login Admin di Pojok Kanan Atas -->
                <a href="{{ route('admin.login') }}" class="bg-[#C9A24B] text-black font-semibold px-4 py-2 rounded-md hover:bg-[#E3C16F] transition text-xs uppercase tracking-wider shadow-[0_0_15px_rgba(201,162,75,0.2)]">
                    Login Admin
                </a>

                <!-- Ikon Keranjang yang terhubung ke CartController & menampilkan jumlah item -->
                <a href="{{ route('cart.index') }}" class="relative p-2 text-[#A8A29E] hover:text-[#C9A24B] transition duration-300 group flex items-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    
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
    </nav>

    <!-- Notifikasi Flash Message -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-emerald-950/80 border border-emerald-500 text-emerald-200 p-4 rounded-xl text-sm flex items-center justify-between shadow-lg">
                <span>{{ session('success') }}</span>
                <a href="{{ route('cart.index') }}" class="underline font-bold hover:text-white transition">Lihat Keranjang &rarr;</a>
            </div>
        </div>
    @endif

    <!-- Hero Section -->
    <header class="relative overflow-hidden border-b border-[#262626]/50 bg-gradient-to-b from-[#141414] to-[#0A0A0A] py-16 md:py-24 text-center px-4">
        <!-- Aksen cahaya gold di background -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-32 bg-[#C9A24B] opacity-[0.03] blur-3xl rounded-full pointer-events-none"></div>
        
        <h1 class="text-4xl md:text-6xl font-serif text-[#F5F0E6] mb-6 tracking-wide">
            Momen Indah, <span class="italic text-[#C9A24B]">Bunga Sempurna</span>
        </h1>
        <p class="text-[#A8A29E] max-w-2xl mx-auto mb-8 text-lg font-light">Temukan rangkaian bunga eksklusif yang dirangkai dengan sepenuh hati untuk setiap momen berharga Anda.</p>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex flex-col lg:flex-row gap-10 relative">
        
        <!-- Sidebar Filter -->
        <aside class="w-full lg:w-1/4">
            <form action="{{ route('home') }}" method="GET" class="bg-gradient-to-b from-[#141414] to-[#0A0A0A] border border-[#262626] rounded-xl p-6 sticky top-28 shadow-2xl">
                <h2 class="text-xl font-serif text-[#C9A24B] mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter Pencarian
                </h2>
                
                <div class="space-y-5">
                    <!-- Pencarian -->
                    <div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama bunga atau tag..." 
                               class="w-full bg-[#0A0A0A] border border-[#262626] rounded-md p-3 text-sm focus:border-[#C9A24B] focus:ring-1 focus:ring-[#C9A24B] text-[#F5F0E6] placeholder-[#A8A29E]/50 transition shadow-inner">
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-xs font-semibold text-[#A8A29E] mb-2 uppercase tracking-widest">Kategori</label>
                        <select name="category" class="w-full bg-[#0A0A0A] border border-[#262626] rounded-md p-3 text-sm focus:border-[#C9A24B] focus:ring-1 focus:ring-[#C9A24B] text-[#F5F0E6] cursor-pointer appearance-none transition">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->slug }}" {{ request('category') == $c->slug ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Acara -->
                    <div>
                        <label class="block text-xs font-semibold text-[#A8A29E] mb-2 uppercase tracking-widest">Acara</label>
                        <select name="occasion" class="w-full bg-[#0A0A0A] border border-[#262626] rounded-md p-3 text-sm focus:border-[#C9A24B] focus:ring-1 focus:ring-[#C9A24B] text-[#F5F0E6] cursor-pointer appearance-none transition">
                            <option value="">Semua Acara</option>
                            @foreach($occasions as $o)
                                <option value="{{ $o->slug }}" {{ request('occasion') == $o->slug ? 'selected' : '' }}>{{ $o->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Cabang -->
                    <div>
                        <label class="block text-xs font-semibold text-[#A8A29E] mb-2 uppercase tracking-widest">Ketersediaan Cabang</label>
                        <select name="branch" class="w-full bg-[#0A0A0A] border border-[#262626] rounded-md p-3 text-sm focus:border-[#C9A24B] focus:ring-1 focus:ring-[#C9A24B] text-[#F5F0E6] cursor-pointer appearance-none transition">
                            <option value="">Semua Cabang</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ request('branch') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Urutkan -->
                    <div>
                        <label class="block text-xs font-semibold text-[#A8A29E] mb-2 uppercase tracking-widest">Urutkan</label>
                        <select name="sort" class="w-full bg-[#0A0A0A] border border-[#262626] rounded-md p-3 text-sm focus:border-[#C9A24B] focus:ring-1 focus:ring-[#C9A24B] text-[#F5F0E6] cursor-pointer appearance-none transition">
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Harga Tertinggi</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col gap-3 mt-8">
                    <button type="submit" class="w-full bg-gradient-to-r from-[#C9A24B] to-[#E3C16F] text-black font-bold py-3 rounded-md hover:opacity-90 transition duration-300 shadow-[0_0_15px_rgba(201,162,75,0.2)]">
                        Terapkan Filter
                    </button>
                    <a href="{{ route('home') }}" class="w-full text-center border border-[#262626] text-[#A8A29E] font-medium py-2.5 rounded-md hover:border-[#A8A29E] hover:text-[#F5F0E6] transition duration-300">
                        Reset
                    </a>
                </div>
            </form>
        </aside>

        <!-- Product Grid / Empty State -->
        <section class="w-full lg:w-3/4">
            @if($flowers->isEmpty())
                <!-- Tampilan Empty State yang lebih jelas -->
                <div class="h-full min-h-[400px] flex flex-col items-center justify-center py-20 px-4 bg-[#141414]/30 border border-[#262626]/50 rounded-2xl border-dashed">
                    <!-- Ikon diperjelas dengan warna gold -->
                    <div class="p-6 bg-[#C9A24B]/10 rounded-full mb-6">
                        <svg class="w-16 h-16 text-[#C9A24B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-serif text-[#F5F0E6] mb-2">Bunga tidak ditemukan</h3>
                    <p class="text-[#A8A29E] text-center max-w-md">Kombinasi pencarian atau filter yang Anda pilih tidak memberikan hasil. Silakan coba kriteria yang berbeda.</p>
                    <a href="{{ route('home') }}" class="mt-8 px-8 py-3 border border-[#C9A24B] text-[#C9A24B] font-medium rounded-md hover:bg-[#C9A24B] hover:text-black transition duration-300">
                        Reset Semua Filter
                    </a>
                </div>
            @else
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($flowers as $flower)
                        <!-- Kartu Produk -->
                        <div class="bg-[#141414] border border-[#262626] hover:border-[#C9A24B]/70 hover:shadow-[0_0_25px_rgba(201,162,75,0.05)] transition-all duration-300 rounded-xl overflow-hidden group flex flex-col">
                            <a href="{{ route('flower.show', $flower->slug) }}" class="flex-grow flex flex-col">
                                <div class="aspect-[4/5] bg-[#0A0A0A] relative overflow-hidden flex items-center justify-center">
                                    @if($flower->primaryImage)
                                        <!-- Pastikan jalur asset storage terpanggil dengan benar -->
                                        <img src="{{ asset('storage/' . $flower->primaryImage->path) }}" alt="{{ $flower->name }}" loading="lazy" class="object-cover w-full h-full group-hover:scale-110 transition duration-700 ease-out opacity-90 group-hover:opacity-100">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center text-[#A8A29E]/50">
                                            <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-xs tracking-widest uppercase">No Image</span>
                                        </div>
                                    @endif
                                    
                                    @if($flower->status === 'sold_out')
                                        <div class="absolute top-3 left-3 bg-red-950/80 backdrop-blur-sm text-red-200 text-xs px-3 py-1.5 rounded text-center border border-red-900/50 font-semibold tracking-widest uppercase">
                                            Habis
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="p-5 flex flex-col flex-grow">
                                    <p class="text-[10px] text-[#A8A29E] mb-2 uppercase tracking-widest font-semibold">{{ $flower->category->name ?? 'Uncategorized' }}</p>
                                    <h3 class="font-serif text-xl leading-snug mb-4 text-[#F5F0E6] group-hover:text-[#C9A24B] transition-colors duration-300">{{ $flower->name }}</h3>
                                    
                                    <div class="mt-auto">
                                        @if($flower->discount_price)
                                            <div class="flex items-center gap-3">
                                                <p class="text-[#C9A24B] font-medium text-lg">Rp {{ number_format($flower->discount_price, 0, ',', '.') }}</p>
                                                <p class="text-[#A8A29E]/60 text-sm line-through">Rp {{ number_format($flower->price, 0, ',', '.') }}</p>
                                            </div>
                                        @else
                                            <p class="text-[#C9A24B] font-medium text-lg">Rp {{ number_format($flower->price, 0, ',', '.') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $flowers->links() }}
                </div>
            @endif
        </section>

    </main>

</body>
</html>