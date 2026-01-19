@extends('layouts.admin')

@section('title', 'Manajemen Coaching Clinic')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Coaching Clinic</h1>
            <p class="text-gray-600">Verifikasi dan kelola pengajuan coaching clinic</p>
        </div>
    </div>

    <!-- Coachings Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Daftar Pengajuan Coaching Clinic</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Kode</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Tanggal</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">OPD</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Layanan</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Agenda</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Status</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($coachings as $coaching)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 font-mono text-sm">
                            CCA{{ $coaching->id_coaching }}
                        </td>
                        <td class="py-3 px-4 text-sm">
                            {{ $coaching->tanggal->format('d/m/Y') }}
                        </td>
                        <td class="py-3 px-4 text-sm">{{ $coaching->user->nama_opd }}</td>
                        <td class="py-3 px-4">
                            <div class="font-medium text-gray-800">{{ $coaching->layanan }}</div>
                        </td>
                        <td class="py-3 px-4 text-sm">{{ Str::limit($coaching->keterangan, 30) }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $coaching->status_verifikasi == 'disetujui' ? 'bg-green-100 text-green-800' : 
                                   ($coaching->status_verifikasi == 'ditolak' ? 'bg-red-100 text-red-800' : 
                                   'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($coaching->status_verifikasi) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex space-x-2">
                                <button onclick="openCoachingModal({{ $coaching->id_coaching }})" 
                                        class="text-blue-600 hover:text-blue-800" title="Verifikasi">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                                <button class="text-gray-600 hover:text-gray-800" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-500">
                            Belum ada pengajuan coaching clinic
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Coaching Verification Modal -->
<div id="coachingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl w-full max-w-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Verifikasi Coaching Clinic</h3>
            <button onclick="closeCoachingModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="coachingForm" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="pending">Pending</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
                
                <div id="coachingDetails" class="space-y-2 text-sm text-gray-600">
                    <!-- Details will be populated by JavaScript -->
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeCoachingModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let currentCoachingId = null;
    
    function openCoachingModal(id) {
        currentCoachingId = id;
        document.getElementById('coachingForm').action = `/admin/coachings/${id}/status`;
        
        // You can fetch coaching details via AJAX here
        // For now, we'll just show the modal
        document.getElementById('coachingModal').classList.remove('hidden');
        document.getElementById('coachingModal').classList.add('flex');
    }
    
    function closeCoachingModal() {
        document.getElementById('coachingModal').classList.remove('flex');
        document.getElementById('coachingModal').classList.add('hidden');
    }
</script>
@endpush
@endsection