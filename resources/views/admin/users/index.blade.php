<x-app-layout>
    <div class="py-12 px-4 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-end mb-10">
                <div>
                    @if (session('success'))
                        <div class="mb-6 p-4 bg-emerald-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] italic animate-bounce flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif
                    <h2 class="text-3xl font-black text-slate-800 tracking-tighter uppercase italic">Manajemen Akun Desa</h2>
                    <p class="text-slate-500 font-bold text-sm tracking-widest uppercase">Buat Akses Operator Desa Belitung Timur</p>
                </div>
                <button onclick="document.getElementById('modalAddUser').showModal()" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl hover:bg-blue-700 transition">
                    + Tambah Operator
                </button>
            </div>

            <div class="bg-white rounded-[3rem] shadow-xl border border-slate-100 overflow-hidden text-sm">
                <table class="w-full text-left">
                    <thead class="bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest">
                        <tr>
                            <th class="p-6">Nama Operator</th>
                            <th class="p-6">Email / Username</th>
                            <th class="p-6">Wilayah Tugas</th>
                            <th class="p-6 text-center">Status</th>
                            <th class="p-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-bold uppercase">
                        @foreach($users as $user)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-6 text-slate-800">{{ $user->name }}</td>
                            <td class="p-6 text-slate-400 lowercase italic">{{ $user->email }}</td>
                            <td class="p-6">
                                <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-[10px]">
                                    {{ $user->desa->nama_desa ?? 'ADMIN KABUPATEN' }}
                                </span>
                            </td>
                            <td class="p-6 text-center">
                                <span class="text-green-500 text-[10px]">● AKTIF</span>
                            </td>
                            <td class="p-6 text-right flex justify-end items-center gap-4">
                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                class="flex items-center gap-2 text-blue-600 hover:text-blue-800 transition-all transform hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span class="text-[10px] font-black uppercase italic tracking-widest">Edit</span>
                                </a>

                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" 
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun operator ini? Data yang dihapus tidak bisa dikembalikan.')" 
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center gap-2 text-slate-300 hover:text-red-600 transition-all transform hover:scale-105">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span class="text-[10px] font-black uppercase italic tracking-widest">Hapus</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <dialog id="modalAddUser" class="p-0 rounded-[3rem] shadow-2xl border-none backdrop:bg-slate-900/50">
        <div class="w-[450px] bg-white p-10 relative">
            
            <div class="absolute top-8 right-8">
                <button type="button" 
                        onclick="document.getElementById('modalAddUser').close()" 
                        class="text-slate-400 hover:text-red-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <h3 class="text-xl font-black text-slate-800 uppercase italic mb-8">Buat Akun Desa</h3>
            
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
                @csrf
                
                <div class="flex flex-col">
                    <label class="text-[10px] font-black uppercase text-slate-400 ml-2 mb-1">Nama Operator</label>
                    <input type="text" name="name" required class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-600 outline-none">
                </div>

                <div class="flex flex-col">
                    <label class="text-[10px] font-black uppercase text-slate-400 ml-2 mb-1">Email</label>
                    <input type="email" name="email" required class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-600 outline-none">
                </div>

                <div class="flex flex-col">
                    <label class="text-[10px] font-black uppercase text-slate-400 ml-2 mb-1">Tugas di Desa</label>
                    <select name="desa_id" required class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-600 outline-none">
                        <option value="">-- PILIH DESA --</option>
                        @foreach($desas as $desa)
                            <option value="{{ $desa->id }}">{{ $desa->nama_desa }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-[10px] font-black uppercase text-slate-400 ml-2 mb-1">Password (Min 8 Karakter)</label>
                    <input type="password" name="password" required class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-600 outline-none">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black text-xs uppercase italic tracking-widest shadow-lg shadow-blue-100 mt-6 hover:bg-blue-700 transition-all">
                    Simpan Akun
                </button>
            </form>
        </div>
    </dialog>
</x-app-layout>