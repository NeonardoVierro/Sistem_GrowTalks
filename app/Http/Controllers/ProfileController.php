<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        return view('user.profil', [
            'user' => Auth::user()
        ]);
    }
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_opd'    => 'required|string|max:150',
            'nama_pic'    => 'required|string|max:150',
            'kontak_pic'  => 'required|string|max:30',
        ], [
            'nama_opd.required' => 'Nama OPD wajib diisi',
            'nama_pic.required' => 'Nama PIC wajib diisi',
            'kontak_pic.required' => 'Kontak PIC wajib diisi',
        ]);

        $user->update([
            'nama_opd'    => $request->nama_opd,
            'nama_pic'    => $request->nama_pic,
            'kontak_pic'  => $request->kontak_pic,
        ]);

        return redirect()
            ->route('user.profile')
            ->with('success', 'Profil berhasil diperbarui');
    }
}
