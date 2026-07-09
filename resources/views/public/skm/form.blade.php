<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKM - {{ $desa->nama_desa }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }

        .radio-card input:checked + .radio-label {
            border-color: #2563eb;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            box-shadow: 0 0 0 2px #2563eb;
        }
        .radio-card input:checked + .radio-label .radio-dot {
            background: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.2);
        }
        .radio-card input:checked + .radio-label .radio-text {
            color: #1e40af;
            font-weight: 800;
        }

        .q-option input[type="radio"]:checked + .q-label {
            background: #2563eb;
            border-color: #2563eb;
            color: white;
            box-shadow: 0 4px 12px rgba(37,99,235,0.35);
            transform: scale(1.03);
        }
        .q-option input[type="radio"]:checked + .q-label .q-num {
            background: rgba(255,255,255,0.25);
            color: white;
        }
        .q-option input[type="radio"]:checked + .q-label .q-text {
            color: white;
            font-weight: 700;
        }
        .q-option .q-label:hover {
            border-color: #93c5fd;
            background: #f0f7ff;
        }
        .q-option input[type="radio"]:checked + .q-label:hover {
            background: #2563eb;
            border-color: #2563eb;
        }

        .step-line { transition: background 0.4s ease; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen">

    <div x-data="skmWizard()" class="py-6 lg:py-10">
        <div class="max-w-2xl mx-auto px-4">

            {{-- ============================================ --}}
            {{-- HEADER REKOMENDASI BPS --}}
            {{-- ============================================ --}}
            <div class="relative overflow-hidden rounded-2xl text-white p-6 lg:p-8 mb-6 shadow-lg"
                 style="background: #0f172a;">

                <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-white/5"></div>
                <div class="absolute right-24 bottom-0 w-32 h-32 rounded-full bg-white/5"></div>

                <div class="absolute top-4 right-6 text-[9px] font-bold text-white/30 uppercase tracking-[0.2em]">
                    Rekom BPS — {{ $rekom->nomor_rekom }}
                </div>

                <h1 class="text-base lg:text-lg font-black uppercase leading-snug pr-44 relative z-10">
                    Survei Kepuasan Masyarakat<br>
                    Terhadap Layanan Publik di<br>
                    Pemerintah Desa {{ strtoupper($desa->nama_desa) }}<br>
                    Kabupaten Belitung Timur
                </h1>

                <div class="mt-4 space-y-1 text-[10px] text-white/50 leading-relaxed max-w-2xl relative z-10">
                    <p>1. Harap isi seluruh pertanyaan. Penilaian hanya boleh memilih satu jawaban pada setiap pertanyaan.</p>
                    <p>2. Hasil survei digunakan sebagai bahan evaluasi peningkatan kualitas pelayanan publik.</p>
                    <p>3. Identitas responden dijamin kerahasiaannya dan tidak akan dipublikasikan.</p>
                    <p>4. Terima kasih atas partisipasi Anda dalam survei kepuasan masyarakat ini.</p>
                </div>

                <div class="mt-5 flex items-end justify-between relative z-10">
                    <p class="text-sm font-black tracking-wider text-white/50">{{ $rekom->kode_survey }}</p>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/15 border border-emerald-500/25 px-4 py-1.5 text-[9px] font-black uppercase tracking-[0.2em] text-emerald-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Survey Aktif
                    </span>
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- WIZARD CONTENT --}}
            {{-- ============================================ --}}
            <form method="POST" action="{{ route('public.skm.store', $desa->slug) }}"
                  @submit.prevent="submitForm()">

                @csrf
                <input type="hidden" name="skm_rekomendasi_id" value="{{ $rekom->id }}">

                {{-- STEP INDICATOR --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-4 px-6 mb-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        @php
                            $steps = [
                                ['num' => 1, 'label' => 'Data Responden'],
                                ['num' => 2, 'label' => 'Informasi Layanan'],
                                ['num' => 3, 'label' => 'Penilaian'],
                                ['num' => 4, 'label' => 'Kirim'],
                            ];
                        @endphp

                        @foreach($steps as $i => $s)
                            <div class="flex items-center {{ !$loop->last ? 'flex-1' : '' }}">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black transition-all duration-300"
                                         :class="currentStep === {{ $s['num'] }} ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : (currentStep > {{ $s['num'] }} ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400')"
                                         x-text="currentStep > {{ $s['num'] }} ? '✓' : '{{ $s["num"] }}'">
                                    </div>
                                    <span class="mt-1.5 text-[8px] font-black uppercase tracking-widest text-center transition-colors duration-300 hidden sm:block"
                                          :class="currentStep >= {{ $s['num'] }} ? 'text-slate-800' : 'text-slate-300'">
                                        {{ $s['label'] }}
                                    </span>
                                </div>

                                @if(!$loop->last)
                                    <div class="flex-1 h-0.5 mx-2 sm:mx-3 mt-[-14px] sm:mt-[-18px] rounded-full step-line"
                                         :class="currentStep > {{ $s['num'] }} ? 'bg-emerald-400' : 'bg-slate-100'">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- STEP 1: DATA RESPONDEN --}}
                {{-- ============================================ --}}
                <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-8"
                     class="bg-white rounded-[2rem] border border-slate-200 p-6 lg:p-8 shadow-sm">

                    <div class="mb-6">
                        <h2 class="text-lg font-black text-slate-900 uppercase italic">Data Responden</h2>
                        <p class="text-xs text-slate-400 mt-1">Lengkapi data diri Anda</p>
                    </div>

                    <div class="space-y-5">
                        {{-- Jenis Kelamin --}}
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Jenis Kelamin <span class="text-red-400">*</span></label>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach(['L' => 'Laki-laki', 'P' => 'Perempuan'] as $val => $label)
                                    <label class="radio-card cursor-pointer">
                                        <input type="radio" name="jenis_kelamin" value="{{ $val }}" required class="sr-only" x-model="step1.jenis_kelamin">
                                        <div class="radio-label flex items-center justify-center gap-2.5 rounded-2xl border-2 border-slate-200 bg-white px-4 py-3.5 transition-all duration-200 hover:border-slate-300">
                                            <span class="radio-dot w-2.5 h-2.5 rounded-full border-2 border-slate-300 transition-all duration-200"></span>
                                            <span class="radio-text text-xs font-bold text-slate-600 transition-all duration-200">{{ $label }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Usia --}}
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Usia <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <input type="number" name="usia" required min="17" max="120" x-model="step1.usia"
                                       class="w-full rounded-2xl border-2 border-slate-200 bg-white px-4 py-3.5 text-sm font-bold text-slate-800 focus:border-blue-500 focus:ring-0 outline-none transition-colors"
                                       placeholder="Masukkan usia Anda">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">tahun</span>
                            </div>
                            <p class="text-[10px] text-slate-300 mt-1">Minimal 18 tahun</p>
                        </div>

                        {{-- Pendidikan --}}
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Pendidikan Terakhir <span class="text-red-400">*</span></label>
                            <div class="grid grid-cols-4 sm:grid-cols-7 gap-2">
                                @foreach(['SD','SMP','SMA','D III','S1','S2','S3'] as $p)
                                    <label class="radio-card cursor-pointer">
                                        <input type="radio" name="pendidikan" value="{{ $p }}" required class="sr-only" x-model="step1.pendidikan">
                                        <div class="radio-label flex items-center justify-center rounded-2xl border-2 border-slate-200 bg-white px-2 py-3 transition-all duration-200 hover:border-slate-300">
                                            <span class="radio-text text-[10px] font-bold text-slate-600 text-center transition-all duration-200">{{ $p }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Pekerjaan --}}
                        <div x-data="{ showLainnya: false }">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Pekerjaan <span class="text-red-400">*</span></label>
                            <div class="grid grid-cols-3 sm:grid-cols-6 gap-2">
                                @foreach(['PNS','TNI','POLRI','SWASTA','WIRAUSAHA','Lainnya'] as $pk)
                                    <label class="radio-card cursor-pointer">
                                        <input type="radio" name="pekerjaan" value="{{ $pk }}" required class="sr-only"
                                               x-model="step1.pekerjaan"
                                               @change="showLainnya = ($event.target.value === 'Lainnya')">
                                        <div class="radio-label flex items-center justify-center rounded-2xl border-2 border-slate-200 bg-white px-2 py-3 transition-all duration-200 hover:border-slate-300">
                                            <span class="radio-text text-[9px] sm:text-[10px] font-bold text-slate-600 text-center transition-all duration-200">{{ $pk }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <div x-show="showLainnya" x-transition class="mt-3">
                                <input type="text" name="pekerjaan_lainnya" x-bind:required="showLainnya"
                                       class="w-full rounded-2xl border-2 border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-800 focus:border-blue-500 focus:ring-0 outline-none"
                                       placeholder="Sebutkan pekerjaan Anda...">
                            </div>
                        </div>
                    </div>

                    {{-- Nav --}}
                    <div class="mt-8 flex justify-end">
                        <button type="button" @click="goToStep(2)"
                                class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-8 py-3.5 text-xs font-black uppercase tracking-widest text-white hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all">
                            Lanjutkan
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- STEP 2: INFORMASI LAYANAN --}}
                {{-- ============================================ --}}
                <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-8"
                     class="bg-white rounded-[2rem] border border-slate-200 p-6 lg:p-8 shadow-sm">

                    <div class="mb-6">
                        <h2 class="text-lg font-black text-slate-900 uppercase italic">Informasi Layanan</h2>
                        <p class="text-xs text-slate-400 mt-1">Pilih layanan yang Anda terima</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Jenis Layanan yang Diterima <span class="text-red-400">*</span></label>
                        <div class="space-y-3">
                            @foreach(['Jasa Layanan Seksi Pemerintahan','Jasa Layanan Seksi Pelayanan','Jasa Layanan Seksi Kesejahteraan Sosial'] as $layanan)
                                <label class="radio-card cursor-pointer block">
                                    <input type="radio" name="layanan_yang_dinilai" value="{{ $layanan }}" required class="sr-only" x-model="step2.layanan">
                                    <div class="radio-label flex items-center gap-4 rounded-2xl border-2 border-slate-200 bg-white px-5 py-4 transition-all duration-200 hover:border-slate-300">
                                        <span class="radio-dot w-3 h-3 rounded-full border-2 border-slate-300 transition-all duration-200 shrink-0"></span>
                                        <span class="radio-text text-sm font-bold text-slate-600 transition-all duration-200">{{ $layanan }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Nav --}}
                    <div class="mt-8 flex items-center justify-between">
                        <button type="button" @click="goToStep(1)"
                                class="inline-flex items-center gap-2 rounded-2xl border-2 border-slate-200 bg-white px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-500 hover:bg-slate-50 transition-all">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            Kembali
                        </button>
                        <button type="button" @click="goToStep(3)"
                                class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-8 py-3.5 text-xs font-black uppercase tracking-widest text-white hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all">
                            Lanjutkan
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- STEP 3: PENILAIAN (SATU PERTANYAAN PER HALAMAN) --}}
                {{-- ============================================ --}}
                <div x-show="currentStep === 3"
                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-8"
                     class="bg-white rounded-[2rem] border border-slate-200 p-6 lg:p-8 shadow-sm">

                    <div class="mb-2">
                        <h2 class="text-lg font-black text-slate-900 uppercase italic">Penilaian Indikator</h2>
                        <p class="text-xs text-slate-400 mt-1">Berikan penilaian Anda pada setiap unsur pelayanan</p>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="mt-5 mb-8">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pertanyaan <span x-text="currentQuestion + 1"></span> dari 9</span>
                            <span class="text-[10px] font-black text-blue-600" x-text="Math.round(((currentQuestion + 1) / 9) * 100) + '%'"></span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full bg-blue-600 transition-all duration-500 ease-out"
                                 :style="'width: ' + ((currentQuestion + 1) / 9 * 100) + '%'"></div>
                        </div>
                    </div>

                    {{-- Question Card --}}
                    <template x-for="(q, idx) in questions" :key="q.id">
                        <div x-show="currentQuestion === idx" x-cloak
                             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-6" x-transition:enter-end="opacity-100 translate-x-0"
                             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-x-6">

                            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5 lg:p-6">
                                <div class="flex items-start gap-4 mb-5">
                                    <span class="shrink-0 w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-xs font-black text-blue-600 shadow-sm"
                                          x-text="'0' + q.id"></span>
                                    <p class="text-sm font-bold text-slate-700 leading-relaxed pt-1.5" x-text="q.text"></p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pl-0 sm:pl-13">
                                    <template x-for="(opt, oi) in q.options" :key="oi">
                                        <label class="q-option cursor-pointer block">
                                            <input type="radio" :name="'q' + q.id" :value="oi + 1" required class="sr-only" x-model="step3['q' + q.id]">
                                            <div class="q-label flex items-center gap-3 rounded-xl border-2 border-slate-200 bg-white px-4 py-4 transition-all duration-200">
                                                <span class="q-num shrink-0 w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-[11px] font-black text-slate-500 transition-all duration-200" x-text="oi + 1"></span>
                                                <span class="q-text text-xs font-bold text-slate-500 leading-snug transition-all duration-200" x-text="opt"></span>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Nav --}}
                    <div class="mt-8 flex items-center justify-between">
                        <button type="button" @click="prevQuestion()"
                                class="inline-flex items-center gap-2 rounded-2xl border-2 border-slate-200 bg-white px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-500 hover:bg-slate-50 transition-all">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            Kembali
                        </button>
                        <button type="button" @click="nextQuestion()"
                                class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-8 py-3.5 text-xs font-black uppercase tracking-widest text-white hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all">
                            <span x-text="currentQuestion === 8 ? 'Lanjutkan' : 'Selanjutnya'"></span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- STEP 4: SARAN & KIRIM --}}
                {{-- ============================================ --}}
                <div x-show="currentStep === 4"
                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-8"
                     class="bg-white rounded-[2rem] border border-slate-200 p-6 lg:p-8 shadow-sm">

                    <div class="mb-6">
                        <h2 class="text-lg font-black text-slate-900 uppercase italic">Saran & Masukan</h2>
                        <p class="text-xs text-slate-400 mt-1">Bagikan pendapat Anda untuk peningkatan layanan (opsional)</p>
                    </div>

                    <textarea name="saran" rows="5"
                              class="w-full rounded-2xl border-2 border-slate-200 bg-white px-5 py-4 text-sm font-bold text-slate-800 placeholder:text-slate-300 placeholder:font-medium focus:border-blue-500 focus:ring-0 outline-none transition-colors resize-none"
                              placeholder="Tuliskan saran atau masukan Anda di sini..."></textarea>

                    {{-- Ringkasan Cepat --}}
                    <div class="mt-6 rounded-2xl bg-slate-50 border border-slate-100 p-5">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Ringkasan</p>
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div>
                                <span class="text-slate-400">Responden</span>
                                <p class="font-bold text-slate-700" x-text="(step1.jenis_kelamin || '-') + ' · ' + (step1.usia || '-') + ' thn'"></p>
                            </div>
                            <div>
                                <span class="text-slate-400">Pendidikan</span>
                                <p class="font-bold text-slate-700" x-text="step1.pendidikan || '-'"></p>
                            </div>
                            <div class="col-span-2">
                                <span class="text-slate-400">Layanan</span>
                                <p class="font-bold text-slate-700" x-text="step2.layanan || '-'"></p>
                            </div>
                            <div class="col-span-2">
                                <span class="text-slate-400">Jawaban terisi</span>
                                <p class="font-bold text-emerald-600" x-text="Object.values(step3).filter(v => v !== null).length + ' dari 9 pertanyaan'"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Nav --}}
                    <div class="mt-8 flex items-center justify-between">
                        <button type="button" @click="goToStep(3)"
                                class="inline-flex items-center gap-2 rounded-2xl border-2 border-slate-200 bg-white px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-500 hover:bg-slate-50 transition-all">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            Kembali
                        </button>
                        <button type="submit" :disabled="submitting"
                                class="inline-flex items-center gap-2 rounded-2xl px-10 py-3.5 text-xs font-black uppercase tracking-widest text-white shadow-lg hover:shadow-xl transition-all duration-200 disabled:opacity-70 disabled:cursor-wait"
                                style="background: linear-gradient(135deg, #2563eb, #0f766e);">
                            <svg x-show="!submitting" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            <svg x-show="submitting" class="animate-spin" width="16" height="16" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span x-text="submitting ? 'Mengirim...' : 'Kirim Survei'"></span>
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>

    <script>
        function skmWizard() {
            return {
                currentStep: 1,
                currentQuestion: 0,
                submitting: false,

                step1: {
                    jenis_kelamin: null,
                    usia: null,
                    pendidikan: null,
                    pekerjaan: null,
                },

                step2: {
                    layanan: null,
                },

                step3: {},

                questions: @json($questions),

                goToStep(step) {
                    // Validasi sebelum lanjut
                    if (step === 2 && this.currentStep === 1) {
                        if (!this.step1.jenis_kelamin || !this.step1.usia || !this.step1.pendidikan || !this.step1.pekerjaan) {
                            alert('Harap lengkapi semua data responden.');
                            return;
                        }
                        if (parseInt(this.step1.usia) < 18) {
                            alert('Usia minimal 18 tahun.');
                            return;
                        }
                    }

                    if (step === 3 && this.currentStep === 2) {
                        if (!this.step2.layanan) {
                            alert('Harap pilih jenis layanan.');
                            return;
                        }
                    }

                    if (step === 3) {
                        this.currentQuestion = 0;
                    }

                    this.currentStep = step;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                nextQuestion() {
                    const q = this.questions[this.currentQuestion];
                    if (!this.step3['q' + q.id]) {
                        alert('Harap pilih salah satu jawaban.');
                        return;
                    }

                    if (this.currentQuestion < 8) {
                        this.currentQuestion++;
                    } else {
                        this.goToStep(4);
                    }
                },

                prevQuestion() {
                    if (this.currentQuestion > 0) {
                        this.currentQuestion--;
                    } else {
                        this.goToStep(2);
                    }
                },

                submitForm() {
                    // Validasi semua jawaban
                    let missing = [];
                    this.questions.forEach(q => {
                        if (!this.step3['q' + q.id]) {
                            missing.push(q.id);
                        }
                    });

                    if (missing.length > 0) {
                        alert('Masih ada ' + missing.length + ' pertanyaan yang belum dijawab.');
                        this.currentStep = 3;
                        // Pergi ke pertanyaan pertama yang belum dijawab
                        const firstMissing = missing[0];
                        const idx = this.questions.findIndex(q => q.id === firstMissing);
                        this.currentQuestion = idx;
                        return;
                    }

                    this.submitting = true;
                    // Beri waktu untuk animasi loading
                    setTimeout(() => {
                        document.querySelector('form').submit();
                    }, 600);
                }
            }
        }
    </script>

</body>
</html>