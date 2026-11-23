<x-guest-layout>
    <div class="bg-white rounded-3xl p-8 sm:p-9 border border-zinc-200/80 shadow-xl relative overflow-hidden">
        {{-- Header Form --}}
        <div class="mb-8 pb-5 border-b border-zinc-100">
            <span class="text-[10px] font-mono-tag uppercase tracking-wider text-zinc-400 block mb-1.5">// ACCESS TERMINAL</span>
            <h2 class="text-2xl font-extrabold text-zinc-950 tracking-tight">Masuk ke Sistem</h2>
            <p class="text-xs text-zinc-500 mt-1">Gunakan kredensial akun terverifikasi Anda untuk mengakses portal.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-5" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">Alamat Email</label>
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       autocomplete="username"
                       placeholder="nama@kampus.ac.id"
                       class="block w-full px-4 py-2.5 rounded-xl border border-zinc-200 text-sm focus:border-zinc-950 focus:ring-1 focus:ring-zinc-950 transition-colors" />
                <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-rose-600" />
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-xs font-mono-tag uppercase text-zinc-600">Kata Sandi</label>
                    @if (Route::has('password.request'))
                        <a class="text-[11px] text-zinc-400 hover:text-zinc-900 underline transition-colors" href="{{ route('password.request') }}">
                            Lupa kata sandi?
                        </a>
                    @endif
                </div>
                <input id="password" 
                       type="password" 
                       name="password" 
                       required 
                       autocomplete="current-password"
                       placeholder="••••••••"
                       class="block w-full px-4 py-2.5 rounded-xl border border-zinc-200 text-sm focus:border-zinc-950 focus:ring-1 focus:ring-zinc-950 transition-colors" />
                <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-rose-600" />
            </div>

            <!-- Remember Me -->
            <div class="pt-1">
                <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                    <input id="remember_me" type="checkbox" class="rounded border-zinc-300 text-zinc-950 shadow-sm focus:ring-zinc-950" name="remember">
                    <span class="text-xs text-zinc-600">Ingat sesi masuk saya</span>
                </label>
            </div>

            <div class="pt-3">
                <button type="submit" 
                        class="w-full py-3 px-4 rounded-xl bg-zinc-950 text-white font-semibold text-xs tracking-wider uppercase hover:bg-zinc-800 transition-all shadow-md hover:shadow-lg font-mono-tag">
                    Masuk ke Portal
                </button>
            </div>
        </form>

        <div class="mt-6 pt-5 border-t border-zinc-100 text-center">
            <p class="text-xs text-zinc-500">
                Belum memiliki akun pendaftar?
                <a href="{{ route('pendaftar.register') }}" class="font-bold text-zinc-950 hover:underline">
                    Ajukan Permohonan Sekarang
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>
