<x-app-layout>
    <div class="py-10 px-4 bg-slate-50 min-h-screen">
        <div class="max-w-6xl mx-auto">

            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-8 mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-600 mb-2">
                            Layanan Informasi Publik
                        </p>

                        <h1 class="text-2xl md:text-3xl font-black text-[#1e3a8a] uppercase italic tracking-tight">
                            PPID Desa
                        </h1>

                        <p class="mt-3 text-sm text-slate-500 max-w-2xl">
                            Kelola Daftar Informasi Publik desa dan pantau permohonan informasi dari masyarakat.
                        </p>
                    </div>

                    <div class="inline-flex items-center rounded-2xl bg-indigo-50 px-4 py-3 text-indigo-700 text-xs font-black uppercase tracking-widest">
                        Modul PPID
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <a href="{{ route('desa.ppid.dip.index') }}"
                   class="group block bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm hover:shadow-md hover:border-indigo-200 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h5l5 5v9a2 2 0 01-2 2z"/>
                        </svg>
                    </div>

                    <h2 class="text-lg font-black text-slate-800 uppercase italic group-hover:text-indigo-700">
                        Daftar Informasi Publik
                    </h2>

                    <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                        Kelola informasi berkala, serta merta, setiap saat, dan informasi dikecualikan.
                    </p>

                    <div class="mt-6 text-xs font-black text-indigo-600 uppercase tracking-widest">
                        Masuk DIP →
                    </div>
                </a>

                <a href="#"
                   class="group block bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.77 9.77 0 01-4-.8L3 20l1.3-3.5A7.6 7.6 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>

                    <h2 class="text-lg font-black text-slate-800 uppercase italic group-hover:text-emerald-700">
                        Permohonan Informasi
                    </h2>

                    <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                        Lihat, pantau, dan tindak lanjuti permohonan informasi yang dikirim masyarakat.
                    </p>

                    <div class="mt-6 text-xs font-black text-emerald-600 uppercase tracking-widest">
                        Lihat Permohonan →
                    </div>
                </a>

            </div>

        </div>
    </div>
</x-app-layout>