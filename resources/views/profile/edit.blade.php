<x-app-layout>
    <div class="py-12 px-4 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto">
            
            <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-4xl font-black text-slate-900 tracking-tighter uppercase italic">Pengaturan Profil</h2>
                    <p class="text-slate-500 font-bold text-sm tracking-widest uppercase mt-1">Kelola informasi akun & keamanan Anda</p>
                </div>
                <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-black italic">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Login Sebagai</p>
                        <p class="text-sm font-black text-slate-800 uppercase italic">{{ auth()->user()->role }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-slate-100 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-8 opacity-5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        
                        <div class="relative z-10">
                            <h3 class="text-lg font-black uppercase italic text-slate-800 mb-6 border-l-4 border-blue-600 pl-4">Informasi Dasar</h3>
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-slate-100 relative overflow-hidden">
                         <div class="absolute top-0 right-0 p-8 opacity-5 text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        
                        <div class="relative z-10">
                            <h3 class="text-lg font-black uppercase italic text-slate-800 mb-6 border-l-4 border-red-600 pl-4">Keamanan Akun</h3>
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="bg-slate-900 text-white p-8 rounded-[2.5rem] shadow-2xl shadow-slate-400">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-400 mb-6 italic">Ringkasan Akun</h3>
                        <div class="space-y-6">
                            <div class="flex flex-col">
                                <span class="text-slate-500 text-[10px] font-black uppercase tracking-widest">Email Terdaftar</span>
                                <span class="font-bold text-sm">{{ auth()->user()->email }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-slate-500 text-[10px] font-black uppercase tracking-widest">Wilayah Tugas</span>
                                <span class="font-bold text-sm uppercase italic">{{ auth()->user()->desa->nama_desa ?? 'Pusat (Admin)' }}</span>
                            </div>
                            <div class="pt-4 border-t border-slate-800">
                                <p class="text-[9px] text-slate-400 leading-relaxed uppercase font-bold">Terakhir login pada: <br> <span class="text-white">{{ now()->format('d M Y H:i') }}</span></p>
                            </div>
                        </div>
                    </div>
                    @if(auth()->user()->role === 'admin')
                    <div class="bg-red-50 p-8 rounded-[2.5rem] border border-red-100">
                        <h3 class="text-xs font-black uppercase italic text-red-600 mb-4">Hapus Akun</h3>
                        <p class="text-[10px] text-red-400 font-bold uppercase leading-relaxed mb-6">Tindakan ini permanen. Seluruh data akses Anda akan dihapus dari server.</p>
                        @include('profile.partials.delete-user-form')
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>