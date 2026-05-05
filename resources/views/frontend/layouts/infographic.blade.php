{{-- File: resources/views/frontend/layouts/infographic.blade.php --}}

<div class="space-y-16">
    @foreach($categories as $category)
        <section>
            <!-- Judul Kategori dengan Garis Aksen -->
            <div class="flex items-center gap-4 mb-8">
                <div style="background-color: {{ $accentColor }};" class="h-8 w-2 rounded-full"></div>
                <h2 class="text-3xl font-black text-slate-800 uppercase italic tracking-tighter">
                    {{ $category->name }}
                </h2>
            </div>

            <!-- Grid Angka Besar (Highlight) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                @foreach($category->indicators->take(4) as $indicator)
                    @php $stat = $indicator->statistics->where('year', $tahun)->first(); @endphp
                    <div class="bg-white p-6 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 text-center">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                            {{ $indicator->name }}
                        </p>
                        <p style="color: {{ $headerColor }};" class="text-3xl font-black tracking-tighter">
                            {{ $stat ? number_format($stat->value, 0, ',', '.') : '0' }}
                        </p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase">{{ $indicator->unit }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Grafik Lebar -->
            <div class="bg-white p-8 rounded-[3rem] shadow-2xl shadow-slate-200/60 border border-white">
                <div class="h-[400px]">
                    <canvas id="chart-{{ $category->id }}"></canvas>
                </div>
            </div>
        </section>
    @endforeach
</div>