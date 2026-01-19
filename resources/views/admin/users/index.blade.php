@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen User</h1>
            <p class="text-gray-600">Kelola data user dan OPD</p>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Daftar User</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Nama</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Email</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">OPD</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">PIC</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Kontak</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Status</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div class="font-medium text-gray-800">{{ $user->nama_pic }}</div>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600">{{ $user->email }}</td>
                        <td class="py-3 px-4 text-sm text-gray-600">{{ $user->nama_opd }}</td>
                        <td class="py-3 px-4 text-sm text-gray-600">{{ $user->nama_pic }}</td>
                        <td class="py-3 px-4 text-sm text-gray-600">{{ $user->kontak_pic }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                Aktif
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-800" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800" title="Nonaktifkan">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-500">
                            Belum ada data user
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 text-sm text-gray-600">
            Total {{ $users->count() }} user
        </div>
    </div>
</div>
@endsection