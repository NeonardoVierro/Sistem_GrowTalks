@extends('layouts.admin')

@section('title', 'Edit Staff')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Edit Host / Coach</h1>

    @if($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.staffs.update', $staff->id) }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-sm text-gray-700 mb-2">Nama</label>
            <input type="text" name="nama" value="{{ old('nama', $staff->nama) }}" class="w-full px-4 py-2 border rounded" />
        </div>
        <div class="mb-4">
            <label class="block text-sm text-gray-700 mb-2">Role</label>
            <select name="role" class="w-full px-4 py-2 border rounded">
                <option value="host" {{ $staff->role == 'host' ? 'selected' : '' }}>Host</option>
                <option value="coach" {{ $staff->role == 'coach' ? 'selected' : '' }}>Coach</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm text-gray-700 mb-2">No. HP</label>
            <input type="text" name="no_hp" value="{{ old('no_hp', $staff->no_hp) }}" class="w-full px-4 py-2 border rounded" />
        </div>
        <div class="mb-4">
            <label class="block text-sm text-gray-700 mb-2">Bidang</label>
            <input type="text" name="bidang" value="{{ old('bidang', $staff->bidang) }}" class="w-full px-4 py-2 border rounded" />
        </div>
        <div class="flex justify-end">
            <a href="{{ route('admin.staffs.index') }}" class="px-4 py-2 mr-2 border">Batal</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
        </div>
    </form>
</div>
@endsection
