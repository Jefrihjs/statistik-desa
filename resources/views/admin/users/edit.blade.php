<x-app-layout>
    <div class="py-12 px-4 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto">
            
            <div class="flex items-center gap-2 mb-6 text-[10px] font-black uppercase tracking-widest text-slate-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" /></svg>
                <a href="{{ route('admin.users.index') }}" class="hover:text-blue-600 transition">Management User</a>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" /></svg>
                <span class="text-slate-800">Edit Operator</span>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-slate-100">
                <div class="p-8 bg-slate-900 text-white flex justify-between items-center relative overflow-hidden">
                    <div class="relative z-10">
                        <h2 class="text-3xl font-black uppercase italic tracking-tighter">Edit Akun</h2>
                        <p class="text-blue-400 text-[10px] font-black uppercase tracking-[0.2em] mt-1">Operator: {{ $user->name }}</p>
                    </div>
                    <div class="absolute right-0 top-0 opacity-10 translate-x-10 -translate-y-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-40 w-40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                </div>

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="p-10 space-y-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[11px] font-black uppercase text-slate-500 tracking-widest ml-1">Nama Lengkap Operator</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                   class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 font-bold text-slate-700 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all" required>
                            @error('name') <span class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black uppercase text-slate-500 tracking-widest ml-1">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                   class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 font-bold text-slate-700 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all" required>
                            @error('email') <span class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-3 md:col-span-2">
                            <label class="text-[11px] font-black uppercase text-slate-500 tracking-widest ml-1">Penempatan Wilayah Desa</label>
                            <select name="desa_id" class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 font-bold text-slate-700 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all cursor-pointer">
                                @foreach($desas as $desa)
                                    <option value="{{ $desa->id }}" {{ $user->desa_id == $desa->id ? 'selected' : '' }}>
                                        DESA {{ strtoupper($desa->nama_desa) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('desa_id') <span class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="bg-blue-50/50 p-8 rounded-[2rem] border-2 border-dashed border-blue-100 space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-600 rounded-lg text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            <h3 class="text-xs font-black uppercase text-blue-900 tracking-widest italic">Keamanan & Password</h3>
                        </div>
                        
                        <p class="text-[10px] text-blue-500 font-bold uppercase leading-relaxed tracking-wider">
                            ℹ️ Kosongkan kolom di bawah jika tidak ingin merubah password lama user ini.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                            <input type="password" name="password" placeholder="MASUKKAN PASSWORD BARU" 
                                   class="w-full rounded-2xl border-slate-200 bg-white p-4 font-bold text-slate-700 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                            @error('password') <span class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row gap-4 pt-4">
                        <button type="submit" 
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-5 rounded-2xl font-black uppercase italic tracking-widest shadow-xl shadow-blue-500/20 transition-all transform hover:-translate-y-1 active:scale-95">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.users.index') }}" 
                           class="md:w-48 text-center bg-slate-100 hover:bg-slate-200 text-slate-500 py-5 rounded-2xl font-black uppercase italic tracking-widest transition-all">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>