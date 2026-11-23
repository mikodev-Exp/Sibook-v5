<x-guest-layout>
    <div class="bg-white rounded-3xl p-8 sm:p-9 border border-zinc-200/80 shadow-xl relative overflow-hidden">
        {{-- Header Form --}}
        <div class="mb-8 pb-5 border-b border-zinc-100">
            <span class="text-[10px] font-mono-tag uppercase tracking-wider text-zinc-400 block mb-1.5">// IDENTITY REGISTRATION</span>
            <h2 class="text-2xl font-extrabold text-zinc-950 tracking-tight">Buat Akun Akses</h2>
            <p class="text-xs text-zinc-500 mt-1">Daftarkan akun baru untuk memulai permohonan magang di BPS Kota Surabaya.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">Nama Lengkap</label>
                <input id="name" 
                       type="text" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required 
                       autofocus 
                       autocomplete="name"
                       placeholder="Masukkan nama lengkap"
                       class="block w-full px-4 py-2.5 rounded-xl border border-zinc-200 text-sm focus:border-zinc-950 focus:ring-1 focus:ring-zinc-950 transition-colors" />
                <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-xs text-rose-600" />
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">Alamat Email</label>
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autocomplete="username"
                       placeholder="nama@kampus.ac.id"
                       class="block w-full px-4 py-2.5 rounded-xl border border-zinc-200 text-sm focus:border-zinc-950 focus:ring-1 focus:ring-zinc-950 transition-colors" />
                <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-rose-600" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">Kata Sandi</label>
                <input id="password" 
                       type="password" 
                       name="password" 
                       required 
                       autocomplete="new-password"
                       placeholder="Minimal 8 karakter"
                       class="block w-full px-4 py-2.5 rounded-xl border border-zinc-200 text-sm focus:border-zinc-950 focus:ring-1 focus:ring-zinc-950 transition-colors" />
                <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-rose-600" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">Konfirmasi Kata Sandi</label>
                <input id="password_confirmation" 
                       type="password" 
                       name="password_confirmation" 
                       required 
                       autocomplete="new-password"
                       placeholder="Ulangi kata sandi"
                       class="block w-full px-4 py-2.5 rounded-xl border border-zinc-200 text-sm focus:border-zinc-950 focus:ring-1 focus:ring-zinc-950 transition-colors" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-xs text-rose-600" />
            </div>

            <div class="pt-3">
                <button type="submit" 
                        class="w-full py-3 px-4 rounded-xl bg-zinc-950 text-white font-semibold text-xs tracking-wider uppercase hover:bg-zinc-800 transition-all shadow-md hover:shadow-lg font-mono-tag">
                    Daftar Sekarang
                </button>
            </div>
        </form>

        <div class="mt-6 pt-5 border-t border-zinc-100 text-center">
            <p class="text-xs text-zinc-500">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-bold text-zinc-950 hover:underline">
                    Masuk ke Sistem
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>
