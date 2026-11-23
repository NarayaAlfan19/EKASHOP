@extends('admin.layouts.app')
@section('title', 'Katalog Bunga')

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-[#A8A29E] text-sm">Kelola produk bunga yang tampil di halaman utama toko.</p>
    <a href="{{ route('admin.bunga.create') }}" class="bg-[#C9A24B] text-black font-semibold px-5 py-2.5 rounded hover:bg-[#E3C16F] transition text-sm">
        + Tambah Bunga Baru
    </a>
</div>

@if(session('success'))
    <div class="bg-emerald-950/80 border border-emerald-500 text-emerald-200 p-4 rounded-xl text-sm mb-6">
        {{ session('success') }}
    </div>
@endif

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
                    <td class="p-4 text-center">
                        <div class="inline-flex items-center justify-center gap-2">
                            <!-- Tombol Edit (Ikon Pensil) -->
                            <a href="{{ route('admin.bunga.edit', $flower->id) }}"
                               title="Edit"
                               aria-label="Edit {{ $flower->name }}"
                               class="p-2 rounded-lg bg-[#0A0A0A] border border-[#262626] text-[#C9A24B] hover:border-[#C9A24B] hover:bg-[#C9A24B]/10 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>

                            <!-- Tombol Hapus (Ikon Tempat Sampah) -->
                            <form action="{{ route('admin.bunga.destroy', $flower->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus {{ addslashes($flower->name) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        title="Hapus"
                                        aria-label="Hapus {{ $flower->name }}"
                                        class="p-2 rounded-lg bg-[#0A0A0A] border border-[#262626] text-red-400 hover:border-red-500/70 hover:bg-red-950/60 hover:text-red-300 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
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