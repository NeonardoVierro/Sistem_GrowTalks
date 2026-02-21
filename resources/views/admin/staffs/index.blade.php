@extends('layouts.admin')

@section('title', 'Manajemen Host & Coach')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Manajemen Host & Coach</h1>
        <a href="{{ route('admin.staffs.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Tambah</a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
    @endif

    <!-- TABEL HOST -->
    <div class="mb-8">
        <h2 class="text-xl font-bold mb-4">Host</h2>
        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full" style="table-layout: fixed;">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="px-4 py-3 text-left" style="width: 25%;">Nama</th>
                        <th class="px-4 py-3 text-left" style="width: 20%;">No. HP</th>
                        <th class="px-4 py-3 text-left" style="width: 35%;">Bidang</th>
                        <th class="px-4 py-3 text-right" style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staffs->where('role', 'host') as $s)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3 break-words">{{ $s->nama }}</td>
                        <td class="px-4 py-3 break-words">{{ $s->no_hp ?? '-' }}</td>
                        <td class="px-4 py-3 break-words">{{ $s->bidang ?? '-' }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.staffs.edit', $s->id) }}" class="text-blue-600 mr-3 hover:text-blue-800" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.staffs.destroy', $s->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus staff ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:text-red-800" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr class="border-t">
                        <td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada data host</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TABEL COACH -->
    <div class="mb-8">
        <h2 class="text-xl font-bold mb-4">Coach</h2>
        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full" style="table-layout: fixed;">
                <thead class="bg-green-100">
                    <tr>
                        <th class="px-4 py-3 text-left" style="width: 25%;">Nama</th>
                        <th class="px-4 py-3 text-left" style="width: 20%;">No. HP</th>
                        <th class="px-4 py-3 text-left" style="width: 35%;">Bidang</th>
                        <th class="px-4 py-3 text-right" style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staffs->where('role', 'coach') as $s)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3 break-words">{{ $s->nama }}</td>
                        <td class="px-4 py-3 break-words">{{ $s->no_hp ?? '-' }}</td>
                        <td class="px-4 py-3 break-words">{{ $s->bidang ?? '-' }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.staffs.edit', $s->id) }}" class="text-blue-600 mr-3 hover:text-blue-800" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.staffs.destroy', $s->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus staff ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:text-red-800" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr class="border-t">
                        <td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada data coach</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
