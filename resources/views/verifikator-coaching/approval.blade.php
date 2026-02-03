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
        
        <!-- Tabs -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <a href="{{ route('verifikator-coaching.approval') }}" 
                   class="py-3 px-6 border-b-2 {{ request('status') == null ? 'border-green-500 text-green-600 font-medium' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Semua ({{ $allCount }})
                </a>
                <a href="{{ route('verifikator-coaching.approval', ['status' => 'pending']) }}" 
                   class="py-3 px-6 border-b-2 {{ request('status') == 'pending' ? 'border-green-500 text-green-600 font-medium' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Pending ({{ $pendingCount }})
                </a>
                <a href="{{ route('verifikator-coaching.approval', ['status' => 'disetujui']) }}" 
                   class="py-3 px-6 border-b-2 {{ request('status') == 'disetujui' ? 'border-green-500 text-green-600 font-medium' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Disetujui ({{ $approvedCount }})
                </a>
                <a href="{{ route('verifikator-coaching.approval', ['status' => 'ditolak']) }}" 
                   class="py-3 px-6 border-b-2 {{ request('status') == 'ditolak' ? 'border-green-500 text-green-600 font-medium' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Ditolak ({{ $rejectedCount }})
                </a>
                <a href="{{ route('verifikator-coaching.approval', ['status' => 'penjadwalan ulang']) }}" 
                   class="py-3 px-6 border-b-2 {{ request('status') == 'penjadwalan ulang' ? 'border-green-500 text-green-600 font-medium' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Penjadwalan Ulang ({{ $rescheduledCount }})
                </a>
            </nav>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-blue-900 text-white">
                        <th class="py-3 px-4 text-left text-sm font-medium">Aksi</th>
                        <th class="py-3 px-4 text-left text-sm font-medium">Status</th>
                        <th class="py-3 px-4 text-left text-sm font-medium">Kode Booking</th>
                        <th class="py-3 px-4 text-left text-sm font-medium">Tanggal</th>
                        <th class="py-3 px-4 text-left text-sm font-medium">Layanan</th>
                        <th class="py-3 px-4 text-left text-sm font-medium">Agenda</th>
                        <th class="py-3 px-4 text-left text-sm font-medium">Instansi</th>
                        <th class="py-3 px-4 text-left text-sm font-medium">PIC</th>
                        <th class="py-3 px-4 text-left text-sm font-medium">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($coachings as $coaching)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <a href="{{ route('verifikator-coaching.approval-form', $coaching->id) }}" 
                               class="text-green-600 hover:text-green-800" title="Verifikasi">
                                <i class="fas fa-file-alt"></i>
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
                            <span class="px-2 py-1 rounded-full text-sm font-medium {{ $bg }}">
                                {{ ucfirst($coaching->status_verifikasi) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-mono text-sm">
                            CCA-{{ date('Ymd', strtotime($coaching->tanggal)) }}{{ $coaching->id }}
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-sm">{{ $coaching->tanggal->locale('id')->isoFormat('D MMMM YYYY') }}</div>
                            @if($coaching->waktu)
                                <div class="text-xs text-gray-600">Waktu: {{ $coaching->waktu }}</div>
                            @else
                                <div class="text-xs text-gray-600">Waktu: -</div>
                            @endif
                            @if($coaching->coach)
                                <div class="text-xs text-gray-600">Coach: {{ $coaching->coach }}</div>
                            @else
                                <div class="text-xs text-gray-600">Coach: -</div>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-medium">{{ $coaching->layanan }}</td>
                        <td class="py-3 px-4">{{ Str::limit($coaching->keterangan, 30) }}</td>
                        <td class="py-3 px-4">{{ $coaching->user->nama_opd ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $coaching->pic }}</td>
                        <td class="py-3 px-4 text-sm text-gray-600">
                            {{ $coaching->catatan ? Str::limit($coaching->catatan, 20) : '-' }}
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