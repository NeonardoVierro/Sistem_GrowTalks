@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="p-4 sm:p-8 max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow border border-gray-200">
        
        <!-- Header -->
        <div class="p-4 sm:p-6 border-b flex justify-between items-center">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800">
                Edit Profil Akun
            </h2>
            <a href="{{ route('user.profile') }}"
                class="text-sm text-gray-500 hover:text-gray-700">
                ✕
            </a>
        </div>

        <!-- Content -->
        <div class="p-4 sm:p-6">
            @if($errors->any())
                <div class="mb-4 p-3 sm:p-4 bg-red-50 border border-red-200 rounded text-sm sm:text-base">
                    <p class="text-red-800 font-medium mb-2">Terdapat kesalahan:</p>
                    <ul class="list-disc list-inside text-red-700 text-xs sm:text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-4 p-3 sm:p-4 bg-green-50 border border-green-200 rounded">
                    <p class="text-green-800 font-medium text-sm sm:text-base">{{ session('success') }}</p>
                </div>
            @endif

            <form action="{{ route('user.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-3 sm:space-y-4">
                    <!-- Email (Read-only) -->
                    <div>
                        <label class="text-xs sm:text-sm text-gray-500">Email</label>
                        <input type="email" 
                               value="{{ Auth::user()->email }}"
                               disabled
                               class="w-full border rounded px-3 py-2 text-sm sm:text-base bg-gray-100 text-gray-600 cursor-not-allowed">
                    </div>

                    <!-- Instansi (Read-only) -->
                    <div>
                        <label class="text-xs sm:text-sm text-gray-500">Instansi/OPD</label>
                        <input type="text" 
                               value="{{ Auth::user()->instansi }}"
                               disabled
                               class="w-full border rounded px-3 py-2 text-sm sm:text-base bg-gray-100 text-gray-600 cursor-not-allowed">
                    </div>

                    <hr class="my-4">

                    <!-- Nama PIC (Editable) -->
                    <div>
                        <label class="text-xs sm:text-sm text-gray-500">Nama PIC <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="nama_pic" 
                               value="{{ old('nama_pic', $user->nama_pic) }}"
                               required
                               placeholder="Masukkan nama lengkap"
                               class="w-full border rounded px-3 py-2 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('nama_pic')
                            <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kontak PIC (Editable) -->
                    <div>
                        <label class="text-xs sm:text-sm text-gray-500">Kontak PIC <span class="text-red-500">*</span></label>
                        <input type="tel" 
                               name="kontak_pic" 
                               value="{{ old('kontak_pic', $user->kontak_pic) }}"
                               required
                               placeholder="08XX XXXX XXXX"
                               class="w-full border rounded px-3 py-2 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('kontak_pic')
                            <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <!-- Password -->
                    <div>
                        <label class="text-xs sm:text-sm text-gray-500">Password Baru (Kosongkan jika tidak diubah)</label>
                        <input type="password" 
                               name="password" 
                               placeholder="Minimal 8 karakter"
                               class="w-full border rounded px-3 py-2 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('password')
                            <p class="text-red-600 text-xs sm:text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="text-xs sm:text-sm text-gray-500">Konfirmasi Password</label>
                        <input type="password" 
                               name="password_confirmation" 
                               placeholder="Ulangi password"
                               class="w-full border rounded px-3 py-2 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-4 sm:p-6 border-t mt-6 flex flex-col-reverse sm:flex-row justify-end gap-2 sm:gap-3">
                    <a href="{{ route('user.profile') }}"
                        class="px-4 py-2 text-sm bg-gray-200 rounded hover:bg-gray-300 text-center">
                        Batal
                    </a>

                    <button type="submit"
                            class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
