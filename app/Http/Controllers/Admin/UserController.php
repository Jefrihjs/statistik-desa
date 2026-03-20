<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('desa')->where('role', 'desa')->latest()->get();
        $desas = Desa::orderBy('nama_desa', 'asc')->get();
        return view('admin.users.index', compact('users', 'desas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'desa_id' => 'required|exists:desas,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'desa_id' => $request->desa_id,
            'role' => 'desa',
        ]);

        return back()->with('success', 'Akun Operator Desa berhasil dibuat!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $desas = Desa::orderBy('nama_desa', 'asc')->get();
        return view('admin.users.edit', compact('user', 'desas'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'desa_id' => 'required|exists:desas,id',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'desa_id' => $request->desa_id,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'Akun operator berhasil diperbarui!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Akun berhasil dihapus!');
    }
}