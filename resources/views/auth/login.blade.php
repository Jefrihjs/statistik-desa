<x-guest-layout>
    <div class="min-h-screen relative flex items-center justify-center overflow-hidden bg-[#f8fafc]">
        
        <div class="absolute inset-0 pointer-events-none opacity-[0.03]" 
             style="background-image: url('{{ asset('img/logo-sicantik.png') }}'); 
                    background-size: 200px; 
                    background-repeat: repeat; 
                    background-position: center;
                    filter: grayscale(100%);">
        </div>

        <div class="absolute inset-0 bg-gradient-to-tr from-violet-50 via-transparent to-pink-50"></div>

        <div class="relative w-full max-w-md bg-white/90 backdrop-blur-xl border border-white rounded-[3rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] p-10 space-y-8">
            
            <div class="text-center space-y-4">
                <div class="inline-block drop-shadow-2xl transform hover:scale-105 transition-all duration-500">
                    <img src="{{ asset('img/logo-sicantik.png') }}" 
                        alt="Logo SiCANTIK" 
                        class="h-24 w-auto mx-auto object-contain animate-pulse-slow">
                </div>
            </div>

            <x-auth-session-status class="mb-4 text-emerald-600 font-bold text-center bg-emerald-50 p-3 rounded-2xl border border-emerald-100" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-500 italic tracking-widest ml-4 text-left block">Alamat Email</label>
                    <input id="email" class="block w-full bg-slate-50 border-slate-200 text-slate-800 placeholder-slate-400 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all outline-none" 
                            type="email" name="email" :value="old('email')" required autofocus placeholder="Masukkan email admin..." />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-pink-600 text-[11px] font-bold px-4" />
                </div>

                <div class="space-y-2" x-data="{ open: false }">
                    <label class="text-[10px] font-black uppercase text-slate-500 italic tracking-widest ml-4 text-left block">Kata Sandi</label>
                    <div class="relative group">
                        <input id="password" 
                            class="block w-full bg-slate-50 border-slate-200 text-slate-800 placeholder-slate-400 rounded-2xl px-6 py-4 pr-14 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all outline-none"
                            x-bind:type="open ? 'text' : 'password'" 
                            name="password" 
                            required 
                            placeholder="••••••••" />
                        
                        <button type="button" x-on:click="open = !open" class="absolute inset-y-0 right-0 pr-6 flex items-center text-slate-400 hover:text-violet-600 transition-colors">
                            <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-pink-600 text-[11px] font-bold px-4" />
                </div>

                <div class="pt-4">
                    <button class="w-full bg-gradient-to-r from-[#1e3a8a] to-[#f59e0b] hover:from-[#1e40af] hover:to-[#fbbf24] text-white font-black uppercase italic py-4 rounded-2xl shadow-xl shadow-blue-900/20 transition-all active:scale-[0.98] tracking-widest text-sm">
                        MASUK SEKARANG
                    </button>
                </div>
            </form>

            <p class="text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest pt-4">
                &copy; 2026 SiCANTIK Belitung Timur
            </p>
        </div>
    </div>

    <style>
        @keyframes pulse-slow {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.9; transform: scale(1.02); }
        }
        .animate-pulse-slow {
            animation: pulse-slow 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</x-guest-layout>