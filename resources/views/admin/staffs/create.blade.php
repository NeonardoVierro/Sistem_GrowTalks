@extends('layouts.admin')

@section('title', 'Tambah Staff')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Tambah Host / Coach</h1>

    @if($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.staffs.store') }}" method="POST" class="bg-white p-6 rounded shadow form-staff-submit">
        @csrf
        <div class="mb-4">
            <label class="block text-sm text-gray-700 mb-2">Nama</label>
            <input type="text" name="nama" value="{{ old('nama') }}" class="w-full px-4 py-2 border rounded" />
        </div>
        <div class="mb-4">
            <label class="block text-sm text-gray-700 mb-2">Role</label>
            <select name="role" class="w-full px-4 py-2 border rounded">
                <option value="host">Host</option>
                <option value="coach">Coach</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm text-gray-700 mb-2">No. HP</label>
            <input type="number" name="no_hp" value="{{ old('no_hp') }}" class="w-full px-4 py-2 border rounded" inputmode="numeric" />
        </div>
        <div class="mb-4">
            <label class="block text-sm text-gray-700 mb-2">Bidang</label>
            <input type="text" name="bidang" value="{{ old('bidang') }}" class="w-full px-4 py-2 border rounded" />
        </div>
        <div class="flex justify-end">
            <a href="{{ route('admin.staffs.index') }}" class="px-4 py-2 mr-2 border">Batal</a>
            <button type="button" class="btn-staff-submit px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const submitBtn = document.querySelector('.btn-staff-submit');
    if (submitBtn) {
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.form-staff-submit');
            Swal.fire({
                title: 'Simpan Staff?',
                text: 'Apakah Anda yakin ingin menyimpan data staff ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }
});
</script>
@endpush
@endsection
