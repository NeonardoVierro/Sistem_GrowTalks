@extends('layouts.verifikator-coaching')

@section('title', 'Approval Coaching Clinic')

@section('content')
<div class="p-8">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Approval Coaching Clinic</h1>
        <p class="text-gray-600 italic">Verifikasi pengajuan coaching clinic dari user</p>
    </div>

    <!-- Antrian Pengajuan Coaching -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Antrian Pengajuan Coaching Clinic</h2>
        </div>
        
        <!-- Filter -->
        <div class="p-6 border-b border-gray-200">
            <form method="GET" action="{{ route('verifikator-coaching.approval') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" 
                           name="search"
                           value="{{ request('search') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500"
                           placeholder="Cari kategori, agenda, coach, atau instansi...">
                </div>
                <div class="w-50">
                    <select name="status"
                            class="w-full px-1 py-2 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="penjadwalan ulang" {{ request('status') == 'penjadwalan ulang' ? 'selected' : '' }}>Penjadwalan Ulang</option>
                    </select>
                </div>
                <div class="w-43">
                    <input type="date" 
                           name="start_date"
                           value="{{ request('start_date') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500">
                </div>
                <div class="w-43">
                    <input type="date" 
                           name="end_date"
                           value="{{ request('end_date') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500">
                </div>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Filter
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="{{ $coachings->count() > 10 ? 'overflow-y-auto max-h-96' : '' }}">
            <table class="w-full">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-blue-900 text-white">
                        <th class="py-3 px-4 text-left text-sm font-medium">Aksi</th>
                        <th class="py-3 px-4 text-left text-sm font-medium">Status</th>
                        <th class="py-3 px-4 text-left text-sm font-medium">Kode Booking</th>
                        <th class="py-3 px-4 text-left text-sm font-medium">Jadwal</th>
                        <th class="py-3 px-4 text-left text-sm font-medium">Layanan</th>
                        <th class="py-3 px-4 text-left text-sm font-medium">Agenda</th>
                        <th class="py-3 px-4 text-left text-sm font-medium">Instansi</th>
                        <th class="py-3 px-4 text-left text-sm font-medium">Coach</th>
                        <th class="py-3 px-4 text-left text-sm font-medium">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($coachings as $coaching)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 flex justify-center items-center">
                            <a href="{{ route('verifikator-coaching.approval-form', $coaching->id) }}" 
                               class="text-blue-700 hover:text-blue-800" title="Verifikasi">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                        <td class="py-3 px-4">
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
                            <span class="inline-flex items-center justify-center w-fit mx-auto
                                        text-[10px] px-2 py-0.5 rounded-full font-medium
                                        whitespace-normal break-words text-center {{ $bg }}">
                                {{ ucfirst($coaching->status_verifikasi) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-mono text-sm">
                            CCA-{{ date('Ymd', strtotime($coaching->tanggal)) }}{{ $coaching->id }}
                        </td>
                        <td class="py-3 px-4 whitespace-nowrap">
                            <div class="text-sm">
                                {{ $coaching->tanggal->locale('id')->isoFormat('D MMMM YYYY') }}
                            </div>
                            @if($coaching->waktu)
                                <div class="text-xs text-gray-600">{{ $coaching->waktu }}</div>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-sm max-w-xs break-words whitespace-normal">{{ $coaching->layanan }}</td>
                        <td class="py-3 px-4 text-sm max-w-xs break-words whitespace-normal">{{ Str::limit($coaching->keterangan, 30) }}</td>
                        <td class="py-3 px-4 text-sm max-w-xs break-words whitespace-normal">{{ $coaching->nama_opd ?? '-' }}</td>
                        <td class="py-3 px-4 text-sm max-w-xs break-words whitespace-normal">{{ $coaching->coach ?? '-' }}</td>
                        <td class="py-3 px-4 text-xs max-w-[180px] whitespace-normal break-words">
                            {{ $coaching->catatan ? Str::limit($coaching->catatan) : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-8 text-center text-gray-500">
                            Belum ada pengajuan coaching clinic
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <!-- Pagination -->
            @if($coachings->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $coachings->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection