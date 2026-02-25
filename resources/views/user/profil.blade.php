@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="p-4 sm:p-8 max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow border border-gray-200">
        
        <!-- Header -->
        <div class="p-4 sm:p-6 border-b flex justify-between items-center">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800">
                Profil Akun
            </h2>
            <a href="{{ route('dashboard') }}"
                class="text-sm text-gray-500 hover:text-gray-700">
                ✕
            </a>
        </div>

        <!-- Content -->
        <div class="p-4 sm:p-6 space-y-4">
            <div>
                <label class="text-xs sm:text-sm text-gray-500">Email</label>
                <p class="font-semibold text-sm sm:text-base">{{ Auth::user()->email }}</p>
            </div>

            <div>
                <label class="text-xs sm:text-sm text-gray-500">Nama OPD</label>
                <p class="font-semibold text-sm sm:text-base">{{ Auth::user()->instansi }}</p>
            </div>

            <div>
                <label class="text-xs sm:text-sm text-gray-500">Nama PIC</label>
                <p class="font-semibold text-sm sm:text-base">{{ Auth::user()->nama_pic }}</p>
            </div>

            <div>
                <label class="text-xs sm:text-sm text-gray-500">Kontak PIC</label>
                <p class="font-semibold text-sm sm:text-base">{{ Auth::user()->kontak_pic }}</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-4 sm:p-6 border-t flex flex-col-reverse sm:flex-row justify-end gap-2 sm:gap-3">
            <a href="{{ route('dashboard') }}"
                class="px-4 py-2 text-sm bg-gray-200 rounded hover:bg-gray-300 text-center">
                Kembali
            </a>

            <a href="{{ route('user.profile.edit') }}"
                class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 text-center">
                Edit Profil
            </a>
        </div>

    </div>
</div>
@endsection
