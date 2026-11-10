<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - EkaShop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#0A0A0A] text-[#F5F0E6] font-sans antialiased flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-[#141414] border-r border-[#262626] flex flex-col">
        <div class="h-20 flex items-center justify-center border-b border-[#262626]">
            <span class="text-2xl font-serif tracking-widest text-[#C9A24B] uppercase">EKASHOP</span>
        </div>
        
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-[#C9A24B] text-black font-bold' : 'text-[#A8A29E] hover:bg-[#262626] hover:text-[#C9A24B]' }} transition">
                Dashboard
            </a>
            <a href="{{ route('admin.bunga.index') }}" class="block px-4 py-3 rounded-md {{ request()->routeIs('admin.bunga.*') ? 'bg-[#C9A24B] text-black font-bold' : 'text-[#A8A29E] hover:bg-[#262626] hover:text-[#C9A24B]' }} transition">
                Katalog Bunga
            </a>
           <a href="{{ route('admin.cabang.index') }}" class="block px-4 py-3 rounded-md {{ request()->routeIs('admin.cabang.*') ? 'bg-[#C9A24B] text-black font-bold' : 'text-[#A8A29E] hover:bg-[#262626] hover:text-[#C9A24B]' }} transition">
                Data Cabang
            </a>
            <a href="{{ route('admin.inquiries.index') }}" class="block px-4 py-3 rounded-md {{ request()->routeIs('admin.inquiries.*') ? 'bg-[#C9A24B] text-black font-bold' : 'text-[#A8A29E] hover:bg-[#262626] hover:text-[#C9A24B]' }} transition">
                Riwayat Pesanan
            </a>
        </nav>

        <div class="p-4 border-t border-[#262626]">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-3 rounded-md text-red-400 hover:bg-red-950 hover:text-red-300 transition">
                    Keluar (Logout)
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-[#0A0A0A]">
        <!-- Topbar -->
        <header class="h-20 bg-[#141414] border-b border-[#262626] flex items-center justify-between px-8">
            <h1 class="text-xl font-medium text-[#F5F0E6]">@yield('title', 'Dashboard')</h1>
            <div class="flex items-center gap-4 text-[#A8A29E]">
                <span class="text-sm">Halo, {{ Auth::user()->name }}</span>
            </div>
        </header>

        <!-- Content Area -->
        <div class="flex-1 overflow-y-auto p-8">
            @if(session('success'))
                <div class="mb-6 bg-[#C9A24B]/10 border border-[#C9A24B] text-[#C9A24B] px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

</body>
</html>