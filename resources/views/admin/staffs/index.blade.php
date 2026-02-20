@extends('layouts.admin')

@section('title', 'Manajemen Host & Coach')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Manajemen Host & Coach</h1>
        <a href="{{ route('admin.staffs.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Tambah</a>
    </div>

    @if(session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Role</th>
                    <th class="px-4 py-3 text-left">No. HP</th>
                    <th class="px-4 py-3 text-left">Bidang</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staffs as $s)
                <tr class="border-t">
                    <td class="px-4 py-3">{{ $s->nama }}</td>
                    <td class="px-4 py-3">{{ ucfirst($s->role) }}</td>
                    <td class="px-4 py-3">{{ $s->no_hp ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $s->bidang ?? '-' }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.staffs.edit', $s->id) }}" class="text-blue-600 mr-3">Edit</a>
                        <form action="{{ route('admin.staffs.destroy', $s->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus staff ini?');">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $staffs->links() }}
    </div>
</div>
@endsection
