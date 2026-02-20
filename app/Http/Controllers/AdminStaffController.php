<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;

class AdminStaffController extends Controller
{
    public function index()
    {
        $staffs = Staff::orderBy('role')->orderBy('nama')->paginate(20);
        return view('admin.staffs.index', compact('staffs'));
    }

    public function create()
    {
        return view('admin.staffs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:191',
            'role' => 'required|in:host,coach',
            'no_hp' => 'nullable|string|max:20',
            'bidang' => 'nullable|string|max:100',
        ]);

        Staff::create($request->only('nama','role','no_hp','bidang'));
        return redirect()->route('admin.staffs.index')->with('success', 'Staff berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        return view('admin.staffs.edit', compact('staff'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:191',
            'role' => 'required|in:host,coach',
            'no_hp' => 'nullable|string|max:20',
            'bidang' => 'nullable|string|max:100',
        ]);

        $staff = Staff::findOrFail($id);
        $staff->update($request->only('nama','role','no_hp','bidang'));

        return redirect()->route('admin.staffs.index')->with('success', 'Staff berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();
        return redirect()->route('admin.staffs.index')->with('success', 'Staff berhasil dihapus.');
    }
}
