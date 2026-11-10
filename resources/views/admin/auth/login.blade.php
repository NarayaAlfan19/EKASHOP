<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - EkaShop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0A0A0A] text-[#F5F0E6] font-sans antialiased h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-[#141414] border border-[#262626] rounded-xl p-8 shadow-2xl">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-serif text-[#C9A24B] tracking-wider">EKASHOP</h1>
            <p class="text-[#A8A29E] text-sm mt-2">Silakan login ke panel admin</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-950/80 border border-red-500 text-red-200 p-3 rounded-md mb-6 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST">
            @csrf
            
            <div class="mb-5">
                <label for="email" class="block text-xs font-semibold text-[#A8A29E] mb-2 uppercase tracking-widest">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full bg-[#0A0A0A] border border-[#262626] rounded-md p-3 text-sm focus:border-[#C9A24B] focus:ring-1 focus:ring-[#C9A24B] text-[#F5F0E6] transition">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-xs font-semibold text-[#A8A29E] mb-2 uppercase tracking-widest">Password</label>
                <input type="password" id="password" name="password" required
                       class="w-full bg-[#0A0A0A] border border-[#262626] rounded-md p-3 text-sm focus:border-[#C9A24B] focus:ring-1 focus:ring-[#C9A24B] text-[#F5F0E6] transition">
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-[#C9A24B] to-[#E3C16F] text-black font-bold py-3 rounded-md hover:opacity-90 transition duration-300 shadow-[0_0_15px_rgba(201,162,75,0.2)]">
                Masuk
            </button>
        </form>
    </div>

</body>
</html>