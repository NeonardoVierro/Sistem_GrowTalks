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
                    
                    <!-- Wrapper untuk toggle antara select dan input -->
                    <div id="instansiWrapper" class="flex gap-2">
                        <select name="instansi" id="instansiSelect" 
                                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent hidden">
                            <option value="">Pilih Instansi</option>
                            @foreach($instansiByKategori as $instansi)
                                <option value="{{ $instansi }}" 
                                    {{ $user->instansi == $instansi ? 'selected' : '' }}>
                                    {{ $instansi }}
                                </option>
                            @endforeach
                        </select>
                        
                        <input type="text" id="instansiInput" 
                               placeholder="Ketik instansi baru..."
                               class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent hidden"
                               value="{{ old('instansi', $user->instansi) }}">
                        
                        <!-- Button untuk toggle input/select -->
                        <button type="button" id="toggleInstansiBtn" 
                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition duration-200"
                                title="Klik untuk mengganti mode input">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                    
                    <!-- Hidden input untuk menyimpan value instansi yang dipilih -->
                    <input type="hidden" name="instansi" id="instansiValue" value="{{ old('instansi', $user->instansi) }}">
                    
                    <p class="mt-2 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        <span id="instansiHelpText">Pilih dari dropdown atau klik tombol edit untuk ketik instansi baru</span>
                    </p>
                    
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
                           placeholder="Minimal 8 karakter">
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
const currentInstansi = '{{ $user->instansi }}';

// State untuk track apakah mode input atau select
let isCustomInput = false;

// Check jika instansi yang ada bukan dari list standar
function isCustomInstansi() {
    const kategori = '{{ $user->kategori_instansi }}';
    if (kategori && instansiData[kategori]) {
        let instansis = instansiData[kategori];
        if (kategori === 'DAFTAR KELURAHAN') {
            instansis = Object.values(instansis).flat();
        }
        return !instansis.includes(currentInstansi);
    }
    return true;
}

// Initialize mode berdasarkan apakah instansi custom atau tidak
function initializeInstansiMode() {
    if (isCustomInstansi()) {
        isCustomInput = true;
        document.getElementById('instansiInput').classList.remove('hidden');
        document.getElementById('instansiSelect').classList.add('hidden');
        document.getElementById('toggleInstansiBtn').innerHTML = '<i class="fas fa-list"></i>';
        document.getElementById('toggleInstansiBtn').title = 'Klik untuk memilih dari dropdown';
        document.getElementById('instansiHelpText').textContent = 'Ketik nama instansi baru yang tidak ada di daftar';
    } else {
        isCustomInput = false;
        document.getElementById('instansiSelect').classList.remove('hidden');
        document.getElementById('instansiInput').classList.add('hidden');
        document.getElementById('toggleInstansiBtn').innerHTML = '<i class="fas fa-edit"></i>';
        document.getElementById('toggleInstansiBtn').title = 'Klik untuk ketik instansi baru';
        document.getElementById('instansiHelpText').textContent = 'Pilih dari dropdown atau klik tombol edit untuk ketik instansi baru';
    }
}

// Update instansi dropdown berdasarkan kategori yang dipilih
document.getElementById('kategori_instansi').addEventListener('change', function() {
    const kategori = this.value;
    const instansiSelect = document.getElementById('instansiSelect');
    
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
    
    // Reset ke mode select
    if (!isCustomInput) {
        instansiSelect.classList.remove('hidden');
    }
});

// Handle toggle button untuk switch antara select dan input
document.getElementById('toggleInstansiBtn').addEventListener('click', function(e) {
    e.preventDefault();
    const instansiSelect = document.getElementById('instansiSelect');
    const instansiInput = document.getElementById('instansiInput');
    const toggleBtn = document.getElementById('toggleInstansiBtn');
    const helpText = document.getElementById('instansiHelpText');
    
    isCustomInput = !isCustomInput;
    
    if (isCustomInput) {
        // Switch ke input mode
        instansiSelect.classList.add('hidden');
        instansiInput.classList.remove('hidden');
        toggleBtn.innerHTML = '<i class="fas fa-list"></i>';
        toggleBtn.title = 'Klik untuk memilih dari dropdown';
        helpText.textContent = 'Ketik nama instansi baru yang tidak ada di daftar';
        instansiInput.focus();
    } else {
        // Switch ke select mode
        instansiSelect.classList.remove('hidden');
        instansiInput.classList.add('hidden');
        toggleBtn.innerHTML = '<i class="fas fa-edit"></i>';
        toggleBtn.title = 'Klik untuk ketik instansi baru';
        helpText.textContent = 'Pilih dari dropdown atau klik tombol edit untuk ketik instansi baru';
    }
});

// Sync value dari select/input ke hidden input
document.getElementById('instansiSelect').addEventListener('change', function() {
    document.getElementById('instansiValue').value = this.value;
});

document.getElementById('instansiInput').addEventListener('input', function() {
    document.getElementById('instansiValue').value = this.value;
});

// Initialize on page load
initializeInstansiMode();

// Form update confirmation
function confirmUpdate() {
    const emailInput = document.getElementById('email');
    const emailValue = emailInput.value;
    const instansiValue = document.getElementById('instansiValue').value;
    
    // Check if instansi is filled
    if (!instansiValue.trim()) {
        Swal.fire({
            title: 'Instansi Belum Dipilih',
            text: 'Pilih instansi dari dropdown atau ketik nama instansi baru',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    // Check if email ends with @solo.go.id
    if (!emailValue.endsWith('@solo.go.id')) {
        Swal.fire({
            title: 'Format Email Tidak Valid',
            html: `
                <div class="text-left">
                    <p class="mb-2">Email harus menggunakan domain @solo.go.id</p>
                    <p class="text-sm text-gray-600">Contoh format yang benar:</p>
                    <ul class="list-disc pl-4 text-sm text-gray-600">
                        <li>dinas.kesehatan@solo.go.id</li>
                        <li>kecamatan.banjarsari@solo.go.id</li>
                        <li>kelurahan.manahan@solo.go.id</li>
                    </ul>
                </div>
            `,
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