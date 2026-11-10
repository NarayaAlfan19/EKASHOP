@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <!-- Kartu Statistik (FR-062, FR-063) -->
    <div class="bg-[#141414] border border-[#262626] rounded-xl p-6">
        <h3 class="text-[#A8A29E] text-sm font-medium uppercase tracking-wider mb-2">Total Produk Bunga</h3>
        <p class="text-4xl font-serif text-[#C9A24B]">{{ $totalFlowers }}</p>
    </div>

    <div class="bg-[#141414] border border-[#262626] rounded-xl p-6">
        <h3 class="text-[#A8A29E] text-sm font-medium uppercase tracking-wider mb-2">Permintaan (7 Hari)</h3>
        <p class="text-4xl font-serif text-[#C9A24B]">{{ $totalOrders }}</p>
    </div>

    <div class="bg-[#141414] border border-[#262626] rounded-xl p-6">
        <h3 class="text-[#A8A29E] text-sm font-medium uppercase tracking-wider mb-2">Cabang Aktif</h3>
        <p class="text-4xl font-serif text-[#C9A24B]">{{ $totalBranches }}</p>
    </div>

</div>

<div class="bg-[#141414] border border-[#262626] rounded-xl p-6">
    <h2 class="text-lg font-medium text-[#F5F0E6] mb-4">Akses Cepat</h2>
    <div class="flex gap-4">
        <a href="{{ route('admin.bunga.create') }}" class="bg-[#C9A24B] text-black font-semibold px-6 py-2.5 rounded hover:bg-[#E3C16F] transition">
            + Tambah Bunga Baru
        </a>
    </div>
</div>
@endsection