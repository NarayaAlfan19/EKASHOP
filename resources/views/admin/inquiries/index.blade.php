@extends('admin.layouts.app')
@section('title', 'Riwayat Permintaan Pesanan')

@section('content')
<div class="mb-6">
    <p class="text-[#A8A29E] text-sm">Daftar riwayat permintaan atau pesanan yang masuk dari pelanggan EkaShop.</p>
</div>

<div class="bg-[#141414] border border-[#262626] rounded-xl overflow-hidden shadow-xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-[#262626] text-xs text-[#A8A29E] uppercase tracking-wider bg-[#0A0A0A]">
                    <th class="p-4">ID / Tanggal</th>
                    <th class="p-4">Pelanggan</th>
                    <th class="p-4">Cabang Tujuan</th>
                    <th class="p-4">Total / Info</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#262626] text-sm text-[#F5F0E6]">
                @forelse($inquiries ?? [] as $inquiry)
                    <tr class="hover:bg-[#1a1a1a] transition">
                        <td class="p-4 text-[#C9A24B]">#{{ $inquiry->id }}</td>
                        <td class="p-4">{{ $inquiry->name ?? '-' }}</td>
                        <td class="p-4 text-[#A8A29E]">{{ $inquiry->branch_id ?? '-' }}</td>
                        <td class="p-4">{{ $inquiry->created_at ?? '-' }}</td>
                        <td class="p-4 text-center">
                            <a href="{{ route('admin.inquiries.show', $inquiry->id) }}" class="text-[#C9A24B] hover:underline text-xs font-semibold">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-[#A8A29E]">Belum ada riwayat permintaan pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection