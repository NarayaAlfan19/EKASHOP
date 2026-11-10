@extends('admin.layouts.app')
@section('title', 'Katalog Bunga')

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-[#A8A29E] text-sm">Kelola produk bunga yang tampil di halaman utama toko.</p>
    <a href="{{ route('admin.bunga.create') }}" class="bg-[#C9A24B] text-black font-semibold px-5 py-2.5 rounded hover:bg-[#E3C16F] transition text-sm">
        + Tambah Bunga Baru
    </a>
</div>

<div class="bg-[#141414] border border-[#262626] rounded-xl overflow-hidden shadow-xl">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-[#262626] text-[#A8A29E] text-xs uppercase tracking-wider bg-[#0A0A0A]/50">
                <th class="p-4">Nama Bunga</th>
                <th class="p-4">Kategori</th>
                <th class="p-4">Harga</th>
                <th class="p-4">Status</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#262626] text-sm">
            @forelse($flowers as $flower)
                <tr class="hover:bg-[#1a1a1a] transition">
                    <td class="p-4 font-medium text-[#F5F0E6]">{{ $flower->name }}</td>
                    <td class="p-4 text-[#A8A29E]">{{ $flower->category->name ?? '-' }}</td>
                    <td class="p-4 text-[#C9A24B]">Rp {{ number_format($flower->price, 0, ',', '.') }}</td>
                    <td class="p-4">
                        <span class="px-2.5 py-1 rounded text-xs font-semibold 
                            {{ $flower->status === 'active' ? 'bg-green-950 text-green-300 border border-green-800' : 'bg-red-950 text-red-300 border border-red-800' }}">
                            {{ ucfirst($flower->status) }}
                        </span>
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <a href="{{ route('admin.bunga.edit', $flower->id) }}" class="text-[#C9A24B] hover:underline">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-[#A8A29E]">Belum ada data bunga. Silakan tambahkan produk baru.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-[#262626]">
        {{ $flowers->links() }}
    </div>
</div>
@endsection