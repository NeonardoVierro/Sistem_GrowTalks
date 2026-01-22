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
   <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-800">Daftar User</h2>
        </div>

        <div class="overflow-x-auto p-2">

            <table class="w-full shadow-md rounded-lg">

                <thead>
                    <thead class="bg-blue-900 text-white">
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Nama</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Email</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">OPD</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">PIC</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Kontak</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Status</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">

                    @forelse($users as $user)

                    <tr class="hover:bg-gray-100 transition duration-200">

                        <td class="py-3 px-4">
                            <div class="font-medium text-gray-800">
                                {{ $user->nama_pic }}
                            </div>
                        </td>

                        <td class="py-3 px-4 text-sm text-gray-600">
                            {{ $user->email }}
                        </td>

                        <td class="py-3 px-4 text-sm text-gray-600">
                            {{ $user->nama_opd }}
                        </td>

                        <td class="py-3 px-4 text-sm text-gray-600">
                            {{ $user->nama_pic }}
                        </td>

                        <td class="py-3 px-4 text-sm text-gray-600">
                            {{ $user->kontak_pic }}
                        </td>

                        <td class="py-3 px-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full shadow bg-green-100 text-green-800">
                                Aktif
                            </span>
                        </td>

                        <td class="py-3 px-4">
                            <div class="flex space-x-2">

                                <button class="text-blue-600 hover:text-blue-800 transition duration-200" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button class="text-red-600 hover:text-red-800 transition duration-200" title="Nonaktifkan">
                                    <i class="fas fa-ban"></i>
                                </button>

                            </div>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="7" class="py-10 text-center text-gray-500">
                            <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 font-semibold">Belum ada data user</p>
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="px-6 py-4 border-t border-gray-200 text-sm text-gray-600 bg-gray-50">
            Total {{ $users->count() }} user
        </div>

    </div>

</div>
@endsection
