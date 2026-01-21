@extends('layouts.verifikator-podcast')

@section('title', 'Form Approval Podcast')

@section('content')
<div class="p-8">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Form Approval Podcast</h1>
        <p class="text-gray-600">Detail dan verifikasi pengajuan podcast</p>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Antrian Pengajuan Podcast</h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-2 gap-6">
                <!-- Left Column -->
                <div>
                    <!-- Kode Booking -->
                    <div class="mb-4">
                        <div class="text-sm text-gray-600 mb-1">Kode Booking</div>
                        <div class="font-mono font-bold text-lg">{{ date('Ymd', strtotime($podcast->tanggal)) }}{{ $podcast->id }}</div>
                    </div>

                    <!-- Tanggal -->
                    <div class="mb-4">
                        <div class="text-sm text-gray-600 mb-1">Tanggal Booking</div>
                        <div class="font-bold">{{ $podcast->tanggal->locale('id')->isoFormat('D MMMM YYYY') }}</div>
                    </div>

                    <!-- Instansi -->
                    <div class="mb-4">
                        <div class="text-sm text-gray-600 mb-1">Instansi</div>
                        <div class="font-bold">{{ $podcast->nama_opd }}</div>
                    </div>

                    <!-- Pemohon -->
                    <div class="mb-4">
                        <div class="text-sm text-gray-600 mb-1">Pemohon</div>
                        <div class="font-bold">{{ $podcast->nama_pic }}</div>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <!-- Judul -->
                    <div class="mb-4">
                        <div class="text-sm text-gray-600 mb-1">Judul Podcast</div>
                        <div class="font-bold">{{ $podcast->keterangan }}</div>
                    </div>

                    <!-- Narasumber -->
                    <div class="mb-4">
                        <div class="text-sm text-gray-600 mb-1">Narasumber</div>
                        <div class="font-bold">{{ $podcast->narasumber }}</div>
                    </div>

                    <!-- Status Saat Ini -->
                    <div class="mb-4">
                        <div class="text-sm text-gray-600 mb-1">Status Permohonan</div>
                        <div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium 
                                {{ $podcast->status_verifikasi == 'disetujui' ? 'bg-green-100 text-green-800' : 
                                   ($podcast->status_verifikasi == 'ditolak' ? 'bg-red-100 text-red-800' : 
                                   'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($podcast->status_verifikasi) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approval Form -->
            <form action="{{ route('verifikator-podcast.update-approval', $podcast->id) }}" method="POST" class="mt-8 border-t border-gray-200 pt-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <!-- Status Verifikasi -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status_verifikasi" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                <option value="pending" {{ $podcast->status_verifikasi == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="disetujui" {{ $podcast->status_verifikasi == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak" {{ $podcast->status_verifikasi == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                <option value="penjadwalan ulang" {{ $podcast->status_verifikasi == 'penjadwalan ulang' ? 'selected' : '' }}>Penjadwalan Ulang</option>
                            </select>
                        </div>

                        <!-- Host -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Host</label>
                            <input type="text" name="host" 
                                   value="{{ $podcast->host }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                   placeholder="Masukkan nama host">
                        </div>
                    </div>

                    <div>
                        <!-- Waktu -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Podcast</label>
                            <input type="text" name="waktu" 
                                   value="{{ $podcast->waktu }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                   placeholder="Contoh: 13.00 - 16.00">
                        </div>

                        <!-- Catatan -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea name="catatan" 
                                      rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                      placeholder="Masukkan catatan">{{ $podcast->catatan }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('verifikator-podcast.approval') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Tutup
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Proses
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection