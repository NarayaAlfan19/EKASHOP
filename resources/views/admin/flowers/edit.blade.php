@extends('admin.layouts.app')
@section('title', 'Edit Bunga')

@section('content')
<div class="max-w-4xl bg-[#141414] border border-[#262626] rounded-xl p-8 shadow-xl">
    
    @if ($errors->any())
        <div class="bg-red-950/80 border border-red-500 text-red-200 p-4 rounded-md mb-6 text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.bunga.update', $flower->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-semibold text-[#A8A29E] mb-2 uppercase tracking-widest">Nama Bunga</label>
                <input type="text" name="name" value="{{ old('name', $flower->name) }}" required
                       class="w-full bg-[#0A0A0A] border border-[#262626] rounded-md p-3 text-sm focus:border-[#C9A24B] text-[#F5F0E6]">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#A8A29E] mb-2 uppercase tracking-widest">Kategori</label>
                <select name="category_id" required class="w-full bg-[#0A0A0A] border border-[#262626] rounded-md p-3 text-sm focus:border-[#C9A24B] text-[#F5F0E6]">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $flower->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-semibold text-[#A8A29E] mb-2 uppercase tracking-widest">Harga Normal (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $flower->price) }}" required
                       class="w-full bg-[#0A0A0A] border border-[#262626] rounded-md p-3 text-sm focus:border-[#C9A24B] text-[#F5F0E6]">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#A8A29E] mb-2 uppercase tracking-widest">Harga Diskon (Opsional)</label>
                <input type="number" name="discount_price" value="{{ old('discount_price', $flower->discount_price) }}"
                       class="w-full bg-[#0A0A0A] border border-[#262626] rounded-md p-3 text-sm focus:border-[#C9A24B] text-[#F5F0E6]">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-[#A8A29E] mb-2 uppercase tracking-widest">Deskripsi</label>
            <textarea name="description" rows="4" required class="w-full bg-[#0A0A0A] border border-[#262626] rounded-md p-3 text-sm focus:border-[#C9A24B] text-[#F5F0E6]">{{ old('description', $flower->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-semibold text-[#A8A29E] mb-2 uppercase tracking-widest">Status</label>
                <select name="status" class="w-full bg-[#0A0A0A] border border-[#262626] rounded-md p-3 text-sm focus:border-[#C9A24B] text-[#F5F0E6]">
                    <option value="active" {{ $flower->status === 'active' ? 'selected' : '' }}>Active (Tampil)</option>
                    <option value="draft" {{ $flower->status === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sold_out" {{ $flower->status === 'sold_out' ? 'selected' : '' }}>Sold Out (Habis)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#A8A29E] mb-2 uppercase tracking-widest">Tambah Foto Baru</label>
                <input type="file" name="images[]" multiple accept="image/*" class="w-full bg-[#0A0A0A] border border-[#262626] rounded-md p-2.5 text-sm text-[#A8A29E] file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#C9A24B] file:text-black">
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t border-[#262626]">
            <a href="{{ route('admin.bunga.index') }}" class="px-6 py-2.5 border border-[#262626] text-[#A8A29E] rounded-md hover:text-white transition">Batal</a>
            <button type="submit" class="bg-[#C9A24B] text-black font-bold px-6 py-2.5 rounded-md hover:bg-[#E3C16F] transition">Perbarui Produk</button>
        </div>
    </form>
</div>
@endsection