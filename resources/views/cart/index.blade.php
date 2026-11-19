<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - EKASHOP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0A0A0A] text-[#F5F0E6] font-sans antialiased selection:bg-[#C9A24B] selection:text-black">

    <!-- Navbar -->
    <header class="bg-[#141414] border-b border-[#262626] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-3xl font-serif tracking-widest bg-gradient-to-r from-[#C9A24B] to-[#E3C16F] bg-clip-text text-transparent">EKASHOP</a>
            <a href="{{ route('home') }}" class="text-[#A8A29E] hover:text-[#C9A24B] font-medium transition">← Lanjut Belanja</a>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-serif text-[#F5F0E6] mb-8">Keranjang Belanja Anda</h1>

        @if(session('success'))
            <div class="bg-emerald-950/80 border border-emerald-500 text-emerald-200 p-4 rounded-xl mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(empty($cart))
            <div class="bg-[#141414] border border-[#262626] rounded-2xl p-12 text-center">
                <p class="text-[#A8A29E] text-lg mb-6">Keranjang belanja Anda masih kosong.</p>
                <a href="{{ route('home') }}" class="inline-block bg-[#C9A24B] text-black font-bold px-6 py-3 rounded-xl hover:bg-[#E3C16F] transition text-sm">
                    Pilih Bunga Sekarang
                </a>
            </div>
        @else
            <div class="bg-[#141414] border border-[#262626] rounded-2xl overflow-hidden shadow-xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#262626] text-xs text-[#A8A29E] uppercase tracking-wider bg-[#0A0A0A]">
                            <th class="p-4">Produk</th>
                            <th class="p-4">Harga</th>
                            <th class="p-4 text-center">Jumlah</th>
                            <th class="p-4">Subtotal</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#262626] text-sm text-[#F5F0E6]">
                        @php $total = 0; @endphp
                        @foreach($cart as $id => $item)
                            @php 
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;
                            @endphp
                            <tr class="hover:bg-[#1a1a1a] transition">
                                <td class="p-4 flex items-center gap-4">
                                    @if(!empty($item['image']))
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded-lg border border-[#262626]">
                                    @else
                                        <div class="w-16 h-16 bg-[#0A0A0A] rounded-lg flex items-center justify-center text-xs text-[#A8A29E]">No Image</div>
                                    @endif
                                    <span class="font-serif font-semibold text-lg text-[#F5F0E6]">{{ $item['name'] }}</span>
                                </td>
                                <td class="p-4 text-[#C9A24B]">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                <td class="p-4 text-center">{{ $item['quantity'] }}</td>
                                <td class="p-4 font-bold text-[#C9A24B]">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                <td class="p-4 text-center">
                                    <form action="{{ route('cart.destroy', $id) }}" method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 font-semibold text-xs transition">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @php
                    $formatWa = function (?string $raw): string {
                        $clean = preg_replace('/[^0-9]/', '', $raw ?? '');
                        if ($clean === '') {
                            return '';
                        }
                        if (str_starts_with($clean, '0')) {
                            return '62' . substr($clean, 1);
                        }
                        return $clean;
                    };

                    $branchesData = [];

                    if (class_exists(\App\Models\Branch::class)) {
                        $dbBranches = \App\Models\Branch::where('is_active', 1)->orderBy('id')->get();

                        foreach ($dbBranches as $branch) {
                            $phone = $formatWa($branch->whatsapp_number);
                            if ($phone !== '') {
                                $branchesData[] = [
                                    'name' => $branch->name,
                                    'phone' => $phone,
                                ];
                            }
                        }
                    }

                    $itemLines = [];
                    foreach ($cart as $item) {
                        $qty = $item['quantity'] ?? 1;
                        $price = $item['price'] ?? 0;
                        $itemSubtotal = $qty * $price;
                        $variantText = !empty($item['variant']) ? " ({$item['variant']})" : "";
                        $itemLines[] = "• {$item['name']}{$variantText} x{$qty} - Rp " . number_format($itemSubtotal, 0, ',', '.');
                    }
                    $itemsBlock = implode("\n", $itemLines);
                    $totalFormatted = number_format($total, 0, ',', '.');
                @endphp

                <div class="p-6 bg-[#0A0A0A] border-t border-[#262626] flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4">
                    <div class="text-lg font-serif">
                        Total Keseluruhan: <span class="text-[#C9A24B] font-bold text-2xl ml-2">Rp {{ $totalFormatted }}</span>
                    </div>

                    @if(!empty($branchesData))
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                            <select id="branchSelect" class="bg-[#141414] border border-[#262626] focus:border-[#C9A24B] text-[#F5F0E6] text-sm rounded-xl px-4 py-3 outline-none transition">
                                @foreach($branchesData as $index => $branch)
                                    <option value="{{ $branch['phone'] }}" data-branch-name="{{ $branch['name'] }}" {{ $index === 0 ? 'selected' : '' }}>
                                        {{ $branch['name'] }}
                                    </option>
                                @endforeach
                            </select>

                            <a id="waCheckoutBtn" href="#" target="_blank" rel="noopener noreferrer"
                               class="bg-emerald-600 hover:bg-emerald-500 text-white font-semibold px-5 py-3 rounded-xl shadow-md transition text-xs sm:text-sm inline-flex items-center justify-center gap-2 whitespace-nowrap">
                                <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12.031 2c-5.516 0-9.969 4.453-9.969 9.969 0 1.758.458 3.475 1.328 4.988L2 22l5.18-1.359a9.927 9.927 0 0 0 4.851 1.242h.004c5.516 0 9.969-4.453 9.969-9.969 0-2.664-1.037-5.168-2.922-7.053A9.912 9.912 0 0 0 12.031 2zm5.816 14.129c-.244.688-1.418 1.316-1.953 1.398-.504.078-1.158.111-1.87-.115-.432-.137-.986-.32-1.695-.627-2.984-1.289-4.934-4.294-5.084-4.493-.149-.199-1.214-1.615-1.214-3.081 0-1.466.768-2.187 1.041-2.486.273-.299.596-.373.795-.373.199 0 .398.002.572.01.184.008.43-.07.672.513.249.597.845 2.062.919 2.211.075.149.124.323.025.522-.099.199-.149.323-.298.497-.149.174-.313.389-.447.522-.149.149-.304.311-.131.609.174.298.773 1.275 1.66 2.065 1.14 1.016 2.101 1.331 2.399 1.48.298.149.472.124.646-.075.174-.199.745-.869.944-1.168.199-.298.398-.249.671-.149.273.099 1.737.819 2.035.968.298.149.497.224.571.348.075.124.075.72-.169 1.408z"/>
                                </svg>
                                <span>Pesan via WhatsApp</span>
                            </a>
                        </div>
                    @else
                        <span class="text-xs text-[#A8A29E]">Belum ada cabang aktif dengan nomor WhatsApp.</span>
                    @endif
                </div>
            </div>

            @if(!empty($branchesData))
                <script>
                    (function () {
                        const selectEl = document.getElementById('branchSelect');
                        const btnEl = document.getElementById('waCheckoutBtn');
                        if (!selectEl || !btnEl) return;

                        const itemsBlock = @json($itemsBlock);
                        const totalBlock = @json($totalFormatted);

                        function buildWaUrl() {
                            const selectedOption = selectEl.options[selectEl.selectedIndex];
                            const phone = selectedOption ? selectedOption.value : '';
                            const branchName = selectedOption
                                ? (selectedOption.dataset.branchName || selectedOption.text.trim())
                                : 'Cabang EKASHOP';

                            const message = [
                                'Halo *EKASHOP*, saya ingin memesan dari *' + branchName + '*:',
                                '',
                                itemsBlock,
                                '',
                                '*Total Keseluruhan: Rp ' + totalBlock + '*',
                                'Mohon informasi ketersediaan dan proses pemesanannya. Terima kasih.'
                            ].join('\n');

                            const url = 'https://wa.me/' + phone + '?text=' + encodeURIComponent(message);
                            btnEl.href = url;
                            return url;
                        }

                        selectEl.addEventListener('change', buildWaUrl);
                        btnEl.addEventListener('click', function () {
                            btnEl.href = buildWaUrl();
                        });

                        buildWaUrl();
                    })();
                </script>
            @endif
        @endif
    </main>

</body>
</html>