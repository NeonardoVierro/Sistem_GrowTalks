@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit User</h1>
            <p class="text-gray-600">Edit data user: {{ $user->nama_pic }}</p>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" id="editForm">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kategori Instansi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori Instansi <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori_instansi" id="kategori_instansi" 
                            required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoriInstansi as $kategori)
                            <option value="{{ $kategori }}" 
                                {{ $user->kategori_instansi == $kategori ? 'selected' : '' }}>
                                {{ $kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_instansi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Instansi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Instansi <span class="text-red-500">*</span>
                    </label>
                    <select name="instansi" id="instansi" 
                            required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Pilih Instansi</option>
                        @foreach($instansiByKategori as $instansi)
                            <option value="{{ $instansi }}" 
                                {{ $user->instansi == $instansi ? 'selected' : '' }}>
                                {{ $instansi }}
                            </option>
                        @endforeach
                    </select>
                    @error('instansi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama PIC -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama PIC <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_pic" value="{{ old('nama_pic', $user->nama_pic) }}"
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Masukkan nama PIC">
                    @error('nama_pic')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kontak PIC -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kontak PIC <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kontak_pic" value="{{ old('kontak_pic', $user->kontak_pic) }}"
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="081234567890">
                    @error('kontak_pic')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="contoh: dinas.kesehatan@solo.go.id">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-gray-500 text-sm">@solo.go.id</span>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Email harus menggunakan domain @solo.go.id
                    </p>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" 
                            required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="aktif" {{ $user->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ $user->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Password (Kosongkan jika tidak diubah)
                    </label>
                    <input type="password" name="password" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Minimal 6 karakter">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password
                    </label>
                    <input type="password" name="password_confirmation"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Ulangi password">
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.users') }}" 
                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-6 rounded-lg transition duration-200">
                    Batal
                </a>
                <button type="button" onclick="confirmUpdate()"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition duration-200">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Data instansi berdasarkan kategori
const instansiData = @json(app('App\Http\Controllers\AdminController')->getInstansiList());

// Update instansi dropdown berdasarkan kategori yang dipilih
document.getElementById('kategori_instansi').addEventListener('change', function() {
    const kategori = this.value;
    const instansiSelect = document.getElementById('instansi');
    
    // Clear existing options
    instansiSelect.innerHTML = '<option value="">Pilih Instansi</option>';
    
    if (kategori && instansiData[kategori]) {
        let instansis = instansiData[kategori];
        
        // Handle nested array (untuk kelurahan)
        if (kategori === 'DAFTAR KELURAHAN') {
            instansis = Object.values(instansis).flat();
        }
        
        instansis.forEach(instansi => {
            const option = document.createElement('option');
            option.value = instansi;
            option.textContent = instansi;
            instansiSelect.appendChild(option);
        });
    }
});

// Form update confirmation
function confirmUpdate() {
    const emailInput = document.getElementById('email');
    const emailValue = emailInput.value;
    
    // Check if email ends with @solo.go.id
    if (!emailValue.endsWith('@solo.go.id')) {
        Swal.fire({
            title: 'Format Email Tidak Valid',
            text: 'Email harus menggunakan domain @solo.go.id',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        emailInput.focus();
        return;
    }
    
    Swal.fire({
        title: 'Konfirmasi Update',
        text: 'Apakah Anda yakin ingin memperbarui data user ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Update!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('editForm').submit();
        }
    });
}

// Error messages from validation
@if($errors->any())
Swal.fire({
    title: 'Perhatian!',
    html: `
        <div class="text-left">
            <p class="mb-2 font-medium">Terdapat kesalahan dalam pengisian form:</p>
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-sm text-red-600">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    `,
    icon: 'error',
    confirmButtonText: 'OK'
});
@endif
</script>
@endsection