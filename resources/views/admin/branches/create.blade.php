@extends('admin.layouts.app')
@section('title', 'Tambah Cabang Baru')

@section('content')
<div class="max-w-2xl bg-[#141414] border border-[#262626] rounded-xl p-8 shadow-xl">
    
    @if ($errors->any())
        <div class="bg-red-950/80 border border-red-500 text-red-200 p-4 rounded-md mb-6 text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.cabang.store') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-[#A8A29E] mb-2 uppercase tracking-widest">Nama Cabang</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full bg-[#0A0A0A] border border-[#262626] rounded-md p-3 text-sm focus:border-[#C9A24B] text-[#F5F0E6]" placeholder="Contoh: EkaShop Cabang Purwokerto">
        </div>

        <div>
            <label class="block text-xs font-semibold text-[#A8A29E] mb-2 uppercase tracking-widest">Alamat Lengkap</label>
            <textarea name="address" rows="3" required
                      class="w-full bg-[#0A0A0A] border border-[#262626] rounded-md p-3 text-sm focus:border-[#C9A24B] text-[#F5F0E6]" placeholder="Masukkan alamat cabang...">{{ old('address') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-semibold text-[#A8A29E] mb-2 uppercase tracking-widest">Nomor Telepon / WhatsApp</label>
                <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number') }}"
                       class="w-full bg-[#0A0A0A] border border-[#262626] rounded-md p-3 text-sm focus:border-[#C9A24B] text-[#F5F0E6]" placeholder="08xxxxxxxxxx">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#A8A29E] mb-2 uppercase tracking-widest">Status Cabang</label>
                <select name="is_active" class="w-full bg-[#0A0A0A] border border-[#262626] rounded-md p-3 text-sm focus:border-[#C9A24B] text-[#F5F0E6]">
                    <option value="1">Active (Aktif)</option>
                    <option value="0">Inactive (Nonaktif)</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-semibold text-[#A8A29E] mb-2 uppercase tracking-widest">Jam Operasional</label>
                <input type="text" name="opening_hours" value="{{ old('opening_hours', '08:00 - 17:00') }}"
                       class="w-full bg-[#0A0A0A] border border-[#262626] rounded-md p-3 text-sm focus:border-[#C9A24B] text-[#F5F0E6]" placeholder="Contoh: 08:00 - 17:00">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#A8A29E] mb-2 uppercase tracking-widest">Google Maps URL (Opsional)</label>
                <input type="text" name="maps_url" value="{{ old('maps_url') }}"
                       class="w-full bg-[#0A0A0A] border border-[#262626] rounded-md p-3 text-sm focus:border-[#C9A24B] text-[#F5F0E6]" placeholder="https://goo.gl/maps/...">
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t border-[#262626]">
            <a href="{{ route('admin.cabang.index') }}" class="px-6 py-2.5 border border-[#262626] text-[#A8A29E] rounded-md hover:text-white transition text-sm">Batal</a>
            <button type="submit" class="bg-[#C9A24B] text-black font-bold px-6 py-2.5 rounded-md hover:bg-[#E3C16F] transition text-sm">Simpan Cabang</button>
        </div>
    </form>
</div>
@endsection