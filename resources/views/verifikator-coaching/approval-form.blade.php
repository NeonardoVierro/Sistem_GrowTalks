@extends('layouts.verifikator-coaching')

@section('title', 'Form Approval Coaching Clinic')

@section('content')
<div class="p-8">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Form Approval Coaching Clinic</h1>
        <p class="text-gray-600">Detail dan verifikasi pengajuan coaching clinic</p>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Antrian Pengajuan Coaching Clinic</h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-2 gap-6">
                <!-- Left Column -->
                <div>
                    <!-- Kode Booking -->
                    <div class="mb-4">
                        <div class="text-sm text-gray-600 mb-1">Kode Booking</div>
                        <div class="font-mono font-bold text-lg">CCA-{{ date('Ymd', strtotime($coaching->tanggal)) }}{{ $coaching->id }}</div>
                    </div>

                    <!-- Tanggal -->
                    <div class="mb-4">
                        <div class="text-sm text-gray-600 mb-1">Tanggal Booking</div>
                        <div class="font-bold">{{ $coaching->tanggal->locale('id')->isoFormat('D MMMM YYYY') }}</div>
                        <div class="text-sm text-gray-600">Hari: {{ $coaching->tanggal->locale('id')->isoFormat('dddd') }}</div>
                    </div>

                    <!-- Instansi -->
                    <div class="mb-4">
                        <div class="text-sm text-gray-600 mb-1">Instansi</div>
                        <div class="font-bold">{{ $coaching->nama_opd ?? '-' }}</div>
                    </div>

                    <!-- Pemohon -->
                    <div class="mb-4">
                        <div class="text-sm text-gray-600 mb-1">Pemohon</div>
                        <div class="font-bold">{{ $coaching->pic }}</div>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <!-- Layanan -->
                    <div class="mb-4">
                        <div class="text-sm text-gray-600 mb-1">Layanan</div>
                        <div class="font-bold">{{ $coaching->layanan }}</div>
                    </div>

                    <!-- Agenda -->
                    <div class="mb-4">
                        <div class="text-sm text-gray-600 mb-1">Agenda</div>
                        <div class="font-bold">{{ $coaching->keterangan }}</div>
                    </div>

                    <!-- Kontak -->
                    <div class="mb-4">
                        <div class="text-sm text-gray-600 mb-1">Kontak</div>
                        <div class="font-bold">{{ $coaching->no_telp }}</div>
                    </div>

                    <!-- Status Saat Ini -->
                    <div class="mb-4">
                        <div class="text-sm text-gray-600 mb-1">Status Permohonan</div>
                        <div>
                            @php
                                $status = strtolower($coaching->status_verifikasi);
                                switch($status) {
                                    case 'disetujui':
                                        $bg = 'bg-green-100 text-green-800';
                                        break;
                                    case 'pending':
                                        $bg = 'bg-yellow-100 text-yellow-800';
                                        break;
                                    case 'ditolak':
                                        $bg = 'bg-red-100 text-red-800';
                                        break;
                                    case 'penjadwalan ulang':
                                        $bg = 'bg-purple-100 text-purple-800';
                                        break;
                                    default:
                                        $bg = 'bg-gray-100 text-gray-800';
                                }
                            @endphp
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $bg }}">
                                {{ ucfirst($coaching->status_verifikasi) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approval Form -->
            <form action="{{ route('verifikator-coaching.update-approval', $coaching->id) }}" method="POST" class="mt-8 border-t border-gray-200 pt-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <!-- Status Verifikasi -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status_verifikasi" 
                                    id="statusSelect"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500">
                                <option value="pending" {{ $coaching->status_verifikasi == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="disetujui" {{ $coaching->status_verifikasi == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak" {{ $coaching->status_verifikasi == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                <option value="penjadwalan ulang" {{ $coaching->status_verifikasi == 'penjadwalan ulang' ? 'selected' : '' }}>Penjadwalan Ulang</option>
                            </select>
                        </div>

                        <!-- Coach -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Coach Penanggung Jawab</label>
                            <select name="coach" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500">
                                <option value="">-- Pilih Coach --</option>
                                @foreach($coaches as $c)
                                    <option value="{{ $c->nama }}" {{ $coaching->coach == $c->nama ? 'selected' : '' }}>{{ $c->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <!-- Waktu -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Coaching</label>
                            <input type="text" name="waktu" 
                                   value="{{ $coaching->waktu }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                   placeholder="Contoh: 09.00 - 11.00">
                        </div>

                        <!-- Catatan -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea name="catatan" 
                                      rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                      placeholder="Masukkan catatan">{{ $coaching->catatan }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('verifikator-coaching.approval') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Tutup
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Proses
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('statusSelect').addEventListener('change', function() {
    const waktuInput = document.querySelector('input[name="waktu"]');
    const coachInput = document.querySelector('input[name="coach"]');
    const selectedStatus = this.value;
    
    if (selectedStatus === 'disetujui') {
        // Waktu dan coach menjadi lebih penting jika disetujui
        waktuInput.parentElement.querySelector('label').innerHTML = 'Waktu Coaching *';
        coachInput.parentElement.querySelector('label').innerHTML = 'Coach Penanggung Jawab *';
    } else {
        waktuInput.parentElement.querySelector('label').innerHTML = 'Waktu Coaching';
        coachInput.parentElement.querySelector('label').innerHTML = 'Coach Penanggung Jawab';
    }
});

// Trigger change on page load
document.getElementById('statusSelect').dispatchEvent(new Event('change'));
</script>
@endpush
@endsection