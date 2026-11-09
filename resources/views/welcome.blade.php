<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EKASHOP - Toko Bunga & Florist Eksklusif</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: {
                            400: '#FACC15',
                            500: '#EAB308',
                            600: '#CA8A04',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-zinc-950 text-zinc-100 antialiased">

    <!-- Navbar -->
    <header class="bg-zinc-900 border-b border-zinc-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-amber-400 tracking-wider">EKASHOP</a>
            <div class="flex items-center space-x-6">
                <a href="{{ route('home') }}" class="text-zinc-300 hover:text-amber-400 font-medium transition">Katalog</a>
                <a href="#branches" class="text-zinc-300 hover:text-amber-400 font-medium transition">Cabang Kami</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-b from-zinc-900 via-zinc-950 to-zinc-950 text-white py-20 px-4 text-center border-b border-zinc-800">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-4xl sm:text-5xl font-extrabold mb-4 tracking-tight">Kirimkan Kemewahan Lewat <span class="text-amber-400">Rangkaian Bunga Indah</span></h1>
            <p class="text-zinc-400 text-lg mb-8">Pesan buket bunga segar berkualitas tinggi untuk setiap momen spesial Anda.</p>
            
            <!-- Search & Filter Form -->
            <form action="{{ route('home') }}" method="GET" class="bg-zinc-900 p-4 rounded-xl border border-zinc-800 shadow-2xl flex flex-col md:flex-row gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari buket bunga..." class="flex-1 px-4 py-2.5 bg-zinc-950 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 text-zinc-100 placeholder-zinc-500">
                
                <select name="category" class="px-4 py-2.5 bg-zinc-950 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 text-zinc-300">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>

                <select name="occasion" class="px-4 py-2.5 bg-zinc-950 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 text-zinc-300">
                    <option value="">Semua Acara</option>
                    @foreach($occasions as $occ)
                        <option value="{{ $occ->slug }}" {{ request('occasion') == $occ->slug ? 'selected' : '' }}>{{ $occ->name }}</option>
                    @endforeach
                </select>

                <button type="submit" class="bg-amber-500 hover:bg-amber-400 text-zinc-950 font-bold px-6 py-2.5 rounded-lg transition shadow-lg">Cari</button>
            </form>
        </div>
    </section>

    <!-- Main Content: Catalog Grid -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-zinc-100">Katalog Produk Pilihan</h2>
            <span class="text-zinc-400 text-sm">Menampilkan produk segar hari ini</span>
        </div>

        @if($flowers->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($flowers as $flower)
                    <div class="bg-zinc-900 rounded-xl shadow-lg hover:shadow-amber-500/10 transition overflow-hidden border border-zinc-800 flex flex-col justify-between">
                        <div>
                            <!-- Gambar Produk -->
                            <div class="h-56 bg-zinc-950 relative overflow-hidden">
                                @if($flower->primaryImage)
                                    <img src="{{ asset('storage/' . $flower->primaryImage->path) }}" alt="{{ $flower->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex items-center justify-center h-full text-zinc-600">No Image</div>
                                @endif
                                @if($flower->is_featured)
                                    <span class="absolute top-2 left-2 bg-amber-500 text-zinc-950 font-bold text-xs px-2.5 py-1 rounded-full shadow">Featured</span>
                                @endif
                            </div>

                            <!-- Info Produk -->
                            <div class="p-4">
                                <span class="text-xs text-amber-400 font-semibold uppercase tracking-wider">{{ $flower->category->name }}</span>
                                <h3 class="font-bold text-lg text-zinc-100 mt-1 line-clamp-1">{{ $flower->name }}</h3>
                                
                                <div class="mt-2 flex items-baseline gap-2">
                                    @if($flower->discount_price)
                                        <span class="text-amber-400 font-bold text-lg">Rp {{ number_format($flower->discount_price, 0, ',', '.') }}</span>
                                        <span class="text-zinc-500 line-through text-sm">Rp {{ number_format($flower->price, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-amber-400 font-bold text-lg">Rp {{ number_format($flower->price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Detail -->
                        <div class="p-4 pt-0">
                            <a href="{{ route('flower.show', $flower->slug) }}" class="block w-full text-center bg-zinc-950 hover:bg-amber-500 hover:text-zinc-950 text-amber-400 font-semibold py-2.5 rounded-lg border border-amber-500/30 transition">Lihat Detail</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $flowers->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-zinc-900 rounded-xl border border-zinc-800">
                <p class="text-zinc-400 text-lg">Maaf, produk bunga yang Anda cari tidak ditemukan.</p>
                <a href="{{ route('home') }}" class="mt-4 inline-block text-amber-400 font-semibold hover:underline">Reset Filter</a>
            </div>
        @endif
    </main>

    <!-- Footer Cabang -->
    <footer id="branches" class="bg-zinc-900 border-t border-zinc-800 py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-xl font-bold mb-6 text-zinc-100">Cabang EKASHOP</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($branches as $branch)
                    <div class="bg-zinc-950 p-6 rounded-xl border border-zinc-800">
                        <h4 class="font-bold text-lg text-amber-400">{{ $branch->name }}</h4>
                        <p class="text-zinc-400 text-sm mt-2">{{ $branch->address }}</p>
                        <p class="text-zinc-500 text-xs mt-1">Jam Buka: {{ $branch->opening_hours }}</p>
                    </div>
                @endforeach
            </div>
            <div class="mt-12 text-center text-zinc-600 text-sm">
                &copy; {{ date('Y') }} EKASHOP Florist. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>