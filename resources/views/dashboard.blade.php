<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-gray-900 tracking-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="space-y-6">
        <x-card>
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-gray-500 font-black mb-3">
                        TARSIUS • Statistik Desa
                    </p>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">
                        Selamat Datang di Pusat Kendali Desa
                    </h1>
                    <p class="mt-3 text-sm leading-relaxed text-gray-600 max-w-2xl">
                        Akses data administrasi, regulasi, statistik, dan layanan desa dalam antarmuka yang rapi, modern, dan responsif.
                    </p>
                </div>

                <div class="rounded-2xl bg-gray-50 border border-gray-100 shadow-sm p-5 min-w-[220px]">
                    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-500 font-black">
                        Status Masuk
                    </p>
                    <p class="mt-2 text-2xl font-black text-slate-900">
                        Berhasil
                    </p>
                    <p class="mt-1 text-sm text-gray-500">
                        Terhubung dengan modul statistik dan layanan.
                    </p>
                </div>
            </div>
        </x-card>

        <x-card>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl bg-gray-50 border border-gray-100 p-5 shadow-sm">
                    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-500 font-black">Modul Administrasi</p>
                    <p class="mt-4 text-2xl font-black text-slate-900">Siap</p>
                </div>
                <div class="rounded-2xl bg-gray-50 border border-gray-100 p-5 shadow-sm">
                    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-500 font-black">Modul Regulasi</p>
                    <p class="mt-4 text-2xl font-black text-slate-900">Terintegrasi</p>
                </div>
                <div class="rounded-2xl bg-gray-50 border border-gray-100 p-5 shadow-sm">
                    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-500 font-black">Modul Statistik</p>
                    <p class="mt-4 text-2xl font-black text-slate-900">Aktif</p>
                </div>
                <div class="rounded-2xl bg-gray-50 border border-gray-100 p-5 shadow-sm">
                    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-500 font-black">Informasi & Keamanan</p>
                    <p class="mt-4 text-2xl font-black text-slate-900">Siap</p>
                </div>
            </div>
        </x-card>
    </div>
</x-app-layout>
