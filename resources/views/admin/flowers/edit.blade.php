@extends('admin.layouts.app')
@section('title', 'Edit Bunga')

@section('content')
<div class="max-w-4xl bg-[#141414] border border-[#262626] rounded-xl p-8 shadow-xl" 
     x-data="{ 
         variants: JSON.parse($el.dataset.variants || '[]'),
         defaultImage: $el.dataset.defaultImage || '',
         addVariant() {
             this.variants.push({ id: null, name: '', price: '' });
         },
         removeVariant(index) {
             this.variants.splice(index, 1);
         }
     }" 
     data-variants="{{ json_encode($flower->variants ?? []) }}"
     data-default-image="{{ $flower->primaryImage ? asset('storage/' . str_replace(['public/', 'storage/'], '', $flower->primaryImage->path)) : '' }}">
    
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
                <label class="block text-xs font-semibold text-[#A8A29E] mb-2 uppercase tracking-widest">Foto Utama Produk (Bisa Banyak)</label>
                <input type="file" name="images[]" multiple accept="image/*" class="w-full bg-[#0A0A0A] border border-[#262626] rounded-md p-2.5 text-sm text-[#A8A29E] file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#C9A24B] file:text-black">
            </div>
        </div>

        <!-- Bagian Kelola Varian Produk (Nama & Harga Saja) -->
        <div class="border-t border-[#262626] pt-6 mt-6">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="text-base font-serif text-[#C9A24B]">Kelola Varian / Model Pilihan</h3>
                    <p class="text-xs text-[#A8A29E]">Ubah, hapus, atau tambahkan varian baru (cth: A, B, C beserta harganya).</p>
                </div>
                <button type="button" @click="addVariant()" class="px-3 py-1.5 bg-[#262626] text-[#F5F0E6] text-xs font-semibold rounded hover:bg-[#C9A24B] hover:text-black transition">
                    + Tambah Varian Baru
                </button>
            </div>

            <div class="space-y-4">
                <template x-for="(variant, index) in variants" :key="index">
                    <div class="flex flex-col md:flex-row gap-4 items-center p-4 bg-[#0A0A0A] rounded-lg border border-[#262626]">
                        <!-- Hidden input untuk menyimpan ID Varian lama -->
                        <input type="hidden" :name="`variants[${index}][id]`" x-model="variant.id">

                        <div class="w-full md:w-5/12">
                            <label class="block text-[10px] text-[#A8A29E] mb-1 uppercase tracking-wider">Nama/Label (cth: A)</label>
                            <input type="text" :name="`variants[${index}][name]`" x-model="variant.name" placeholder="A" required class="w-full bg-[#141414] border border-[#262626] rounded p-2 text-sm text-white">
                        </div>
                        
                        <div class="w-full md:w-5/12">
                            <label class="block text-[10px] text-[#A8A29E] mb-1 uppercase tracking-wider">Harga (Rp)</label>
                            <input type="number" :name="`variants[${index}][price]`" x-model="variant.price" placeholder="50000" required class="w-full bg-[#141414] border border-[#262626] rounded p-2 text-sm text-white">
                        </div>

                        <div class="w-full md:w-2/12 flex justify-end">
                            <button type="button" @click="removeVariant(index)" class="text-red-400 hover:text-red-300 md:mt-5 text-sm font-bold px-3 py-1">
                                ✕ Hapus
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t border-[#262626]">
            <a href="{{ route('admin.bunga.index') }}" class="px-6 py-2.5 border border-[#262626] text-[#A8A29E] rounded-md hover:text-white transition">Batal</a>
            <button type="submit" class="bg-[#C9A24B] text-black font-bold px-6 py-2.5 rounded-md hover:bg-[#E3C16F] transition">Perbarui Produk</button>
        </div>
    </form>
</div>
@endsection