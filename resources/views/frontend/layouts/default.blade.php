{{-- File: resources/views/frontend/layouts/default.blade.php --}}

<div class="space-y-12">
    @foreach($categories as $category)
        <section class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div style="background-color: {{ $headerColor }};" class="px-6 py-4">
                <h2 class="text-xl font-black text-white uppercase tracking-tight">
                    {{ $category->name }}
                </h2>
            </div>

            <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Sisi Kiri: Daftar Angka -->
                <div class="space-y-4">
                    @foreach($category->indicators as $indicator)
                        @php
                            $stat = $indicator->statistics->where('year', $tahun)->first();
                        @endphp
                        <div class="flex justify-between items-center border-b border-slate-50 pb-3">
                            <span class="text-slate-600 font-bold uppercase text-xs tracking-wider">
                                {{ $indicator->name }}
                            </span>
                            <span style="color: {{ $accentColor }};" class="text-lg font-black">
                                {{ $stat ? number_format($stat->value, 0, ',', '.') : '-' }}
                                <small class="text-[10px] text-slate-400 uppercase">{{ $indicator->unit }}</small>
                            </span>
                        </div>
                    @endforeach
                </div>

                <!-- Sisi Kanan: Grafik -->
                <div class="bg-slate-50 rounded-2xl p-4 flex items-center justify-center min-h-[300px]">
                     <canvas id="chart-{{ $category->id }}"></canvas>
                </div>
            </div>
        </section>
    @endforeach
</div>