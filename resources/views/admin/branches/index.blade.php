@extends('admin.layouts.app')
@section('title', 'Data Cabang')

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-[#A8A29E] text-sm">Kelola daftar cabang toko bunga EkaShop.</p>
    <a href="{{ route('admin.cabang.create') }}" class="bg-[#C9A24B] text-black font-semibold px-5 py-2.5 rounded hover:bg-[#E3C16F] transition text-sm">
        + Tambah Cabang Baru
    </a>
</div>

<div class="bg-[#141414] border border-[#262626] rounded-xl overflow-hidden shadow-xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-[#262626] text-xs text-[#A8A29E] uppercase tracking-wider bg-[#0A0A0A]">
                    <th class="p-4">Nama Cabang</th>
                    <th class="p-4">Alamat</th>
                    <th class="p-4">WhatsApp</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#262626] text-sm text-[#F5F0E6]">
                @forelse($branches as $branch)
                    <tr class="hover:bg-[#1a1a1a] transition">
                        <td class="p-4 font-semibold text-[#C9A24B]">{{ $branch->name }}</td>
                        <td class="p-4 text-[#A8A29E]">{{ $branch->address }}</td>
                        <td class="p-4">{{ $branch->whatsapp_number ?? '-' }}</td>
                        <td class="p-4">
                            @if($branch->is_active)
                                <span class="bg-green-950 text-green-400 border border-green-800 px-2.5 py-1 rounded-full text-xs font-medium">Active</span>
                            @else
                                <span class="bg-red-950 text-red-400 border border-red-800 px-2.5 py-1 rounded-full text-xs font-medium">Inactive</span>
                            @endif
                        </td>
                        <td class="p-4 text-center space-x-2">
                            <a href="{{ route('admin.cabang.edit', $branch->id) }}" class="text-[#C9A24B] hover:underline text-xs font-semibold">Edit</a>
                            <form action="{{ route('admin.cabang.destroy', $branch->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus cabang ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:underline text-xs font-semibold">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-[#A8A29E]">Belum ada data cabang yang ditambahkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(method_exists($branches, 'links'))
        <div class="p-4 border-t border-[#262626]">
            {{ $branches->links() }}
        </div>
    @endif
</div>
@endsection