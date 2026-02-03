@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen User</h1>
            <p class="text-gray-600 italic">Kelola data user dan OPD</p>
        </div>
        <a href="{{ route('admin.users.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 flex items-center shadow-sm hover:shadow">
            <i class="fas fa-plus mr-2"></i> Tambah User
        </a>
    </div>

    <!-- Filter and Search Section -->
    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
        <form action="{{ route('admin.users') }}" method="GET" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Kategori Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Instansi</label>
                    <select name="kategori" id="kategoriFilter"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoriInstansi as $kategori)
                            <option value="{{ $kategori }}" 
                                {{ request('kategori') == $kategori ? 'selected' : '' }}>
                                {{ $kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Search Input -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari User</label>
                    <div class="flex space-x-2">
                        <div class="flex-1 relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari nama PIC, email, instansi, atau kontak..."
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3.5 text-gray-400 text-sm"></i>
                        </div>
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-lg transition duration-200 flex items-center shadow-sm hover:shadow">
                            <i class="fas fa-search mr-2"></i> Cari
                        </button>
                        @if(request()->has('search') || request()->has('kategori'))
                        <a href="{{ route('admin.users') }}" 
                           class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2.5 px-5 rounded-lg transition duration-200 flex items-center">
                            <i class="fas fa-times mr-2"></i> Reset
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
        <!-- Table Header -->
        <div class="px-5 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Daftar User</h2>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600">Tampilkan:</span>
                <select id="perPageSelect" 
                        class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-full">
                <thead class="bg-blue-50 border-b border-blue-100">
                    <tr>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-blue-900 uppercase tracking-wider">Email</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-blue-900 uppercase tracking-wider">Kategori</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-blue-900 uppercase tracking-wider">Instansi</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-blue-900 uppercase tracking-wider">PIC</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-blue-900 uppercase tracking-wider">Kontak</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-blue-900 uppercase tracking-wider">Status</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-blue-900 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-blue-50/50 transition duration-150">
                        <!-- Email -->
                        <td class="py-3 px-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-envelope text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Kategori -->
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ Str::limit($user->kategori_instansi, 20) }}
                            </span>
                        </td>

                        <!-- Instansi -->
                        <td class="py-3 px-4">
                            <div class="text-sm text-gray-900 font-medium">{{ $user->instansi }}</div>
                        </td>

                        <!-- PIC -->
                        <td class="py-3 px-4">
                            <div class="text-sm text-gray-900">{{ $user->nama_pic }}</div>
                        </td>

                        <!-- Kontak -->
                        <td class="py-3 px-4">
                            <div class="text-sm text-gray-900">
                                <i class="fas fa-phone-alt text-gray-400 mr-2"></i>
                                {{ $user->kontak_pic }}
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="py-3 px-4">
                            @if($user->status == 'aktif')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1.5"></i> Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1.5"></i> Nonaktif
                            </span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 transition duration-150 p-1.5 hover:bg-blue-50 rounded"
                                   title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                
                                <button onclick="toggleStatus({{ $user->id }}, '{{ $user->status }}')" 
                                        class="text-yellow-600 hover:text-yellow-900 transition duration-150 p-1.5 hover:bg-yellow-50 rounded"
                                        title="{{ $user->status == 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i class="fas {{ $user->status == 'aktif' ? 'fa-ban' : 'fa-check' }} text-sm"></i>
                                </button>
                                
                                <button onclick="confirmDelete({{ $user->id }})" 
                                        class="text-red-600 hover:text-red-900 transition duration-150 p-1.5 hover:bg-red-50 rounded"
                                        title="Hapus">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-users text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-gray-500 font-medium">Tidak ada data user</p>
                                @if(request()->has('search') || request()->has('kategori'))
                                <p class="text-gray-400 text-sm mt-1">Coba ubah filter atau kata kunci pencarian</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="px-5 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="text-sm text-gray-600 mb-3 md:mb-0">
                    Menampilkan <span class="font-medium">{{ $users->firstItem() }}</span> - 
                    <span class="font-medium">{{ $users->lastItem() }}</span> dari 
                    <span class="font-medium">{{ $users->total() }}</span> user
                </div>
                <div>
                    {{ $users->appends([
                        'per_page' => request('per_page', 10), 
                        'search' => request('search'),
                        'kategori' => request('kategori')
                    ])->links() }}
                </div>
            </div>
        </div>
        @elseif($users->count() > 0)
        <div class="px-5 py-4 border-t border-gray-200 text-sm text-gray-600 bg-gray-50">
            Total {{ $users->count() }} user
        </div>
        @endif
    </div>

</div>

<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Auto submit filter when kategori changes
document.getElementById('kategoriFilter').addEventListener('change', function() {
    document.getElementById('filterForm').submit();
});

// Per page selector
document.getElementById('perPageSelect').addEventListener('change', function() {
    const perPage = this.value;
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', perPage);
    window.location.href = url.toString();
});

// Toggle Status Confirmation
function toggleStatus(userId, currentStatus) {
    const action = currentStatus === 'aktif' ? 'nonaktifkan' : 'aktifkan';
    const statusText = currentStatus === 'aktif' ? 'Nonaktif' : 'Aktif';
    
    Swal.fire({
        title: `Konfirmasi ${statusText}kan User`,
        text: `Apakah Anda yakin ingin ${action} user ini?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Ya, ${action}`,
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `/admin/users/${userId}/toggle-status`;
        }
    });
}

// Delete Confirmation dengan form yang benar
function confirmDelete(userId) {
    Swal.fire({
        title: 'Hapus User?',
        text: "Data user akan dihapus permanen. Tindakan ini tidak dapat dibatalkan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Buat form delete
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/users/${userId}`;
            
            // Tambahkan CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = csrfToken;
            
            form.appendChild(methodInput);
            form.appendChild(tokenInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Success Message from Session
@if(session('success'))
Swal.fire({
    title: 'Berhasil!',
    text: '{{ session("success") }}',
    icon: 'success',
    confirmButtonColor: '#3085d6',
    confirmButtonText: 'OK'
});
@endif

// Error Message
@if(session('error'))
Swal.fire({
    title: 'Gagal!',
    text: '{{ session("error") }}',
    icon: 'error',
    confirmButtonColor: '#3085d6',
    confirmButtonText: 'OK'
});
@endif
</script>
@endsection