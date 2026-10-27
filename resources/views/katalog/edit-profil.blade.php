<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - {{ $user->nama_anggota }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7fafc;
        }

        #foto-input {
            display: none;
        }
    </style>
</head>

<body class="antialiased">
    @include('partials.modals.navbar')

    <section class="py-12 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <header class="mb-12 text-center">
                <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Edit Profil</h1>
                <p class="mt-2 text-lg text-gray-500">Perbarui informasi dan pengaturan akun Anda di sini.</p>
            </header>

            {{-- MULAI FORM UTAMA --}}
            <form action="{{ route('profil.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                    {{-- KOLOM KIRI: UPLOAD FOTO --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <div class="p-6 bg-gradient-to-tr from-indigo-600 to-purple-600 text-white">
                                <h3 class="text-xl font-bold">Foto Profil</h3>
                                <p class="text-sm opacity-90 mt-1">Ganti foto profil Anda.</p>
                            </div>

                            <div class="p-6 text-center">
                                <div class="relative w-36 h-36 mx-auto group">
                                    <img id="foto-preview"
                                        class="h-full w-full rounded-full object-cover border-4 border-white shadow-lg"
                                        src="{{ $user->foto ? asset('storage/' . $user->foto) : 'https://placehold.co/200x200/EBF4FF/7F9CF5?text=' . substr($user->nama_anggota, 0, 1) }}"
                                        alt="Foto Profil {{ $user->nama_anggota }}"
                                        data-original-src="{{ $user->foto ? asset('storage/' . $user->foto) : 'https://placehold.co/200x200/EBF4FF/7F9CF5?text=' . substr($user->nama_anggota, 0, 1) }}">

                                    <label for="foto-input"
                                        class="absolute inset-0 w-full h-full flex items-center justify-center bg-black bg-opacity-60 rounded-full cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </label>
                                </div>

                                <input type="file" name="foto" id="foto-input" class="hidden" accept="image/*">

                                <div class="mt-4 h-5">
                                    <span id="file-name-display" class="text-sm text-slate-500 italic"></span>
                                </div>

                                <button type="button" id="reset-foto-btn"
                                    class="mt-2 px-4 py-2 text-sm font-semibold text-indigo-800 bg-indigo-100 rounded-lg hover:bg-indigo-200 transition-colors">
                                    Reset Pilihan
                                </button>
                                <p class="text-xs text-slate-400 mt-3">JPG, GIF atau PNG. Maks 2MB.</p>
                            </div>
                        </div>
                    </div>

                    {{-- KOLOM KANAN: INFORMASI & PASSWORD --}}
                    <div class="lg:col-span-2 space-y-8">
                        {{-- KARTU INFORMASI PRIBADI --}}
                        <div class="bg-white rounded-2xl shadow-lg">
                            <div
                                class="p-6 md:p-8 bg-slate-50 rounded-t-2xl border-b border-slate-200 flex items-center gap-4">
                                <div
                                    class="w-12 h-12 flex-shrink-0 bg-gradient-to-br from-indigo-500 to-purple-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-800">Informasi Pribadi</h3>
                                    <p class="text-sm text-slate-500 mt-1">Data diri yang akan ditampilkan di profil
                                        Anda.</p>
                                </div>
                            </div>
                            <div class="p-6 md:p-8 space-y-6">
                                <div>
                                    <label for="nama_anggota"
                                        class="block text-sm font-semibold text-gray-800 mb-2">Nama Lengkap</label>
                                    <input type="text" id="nama_anggota" name="nama_anggota"
                                        value="{{ old('nama_anggota', $user->nama_anggota) }}" required
                                        class="block w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label for="no_telepon" class="block text-sm font-semibold text-gray-800 mb-2">Nomor
                                        Telepon</label>
                                    <input type="text" id="no_telepon" name="no_telepon"
                                        value="{{ old('no_telepon', $user->no_telepon) }}"
                                        class="block w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label for="alamat"
                                        class="block text-sm font-semibold text-gray-800 mb-2">Alamat</label>
                                    <textarea id="alamat" name="alamat" rows="3"
                                        class="block w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('alamat', $user->alamat) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- KARTU UBAH PASSWORD --}}
                        <div class="bg-white rounded-2xl shadow-lg">
                            <div
                                class="p-6 md:p-8 bg-slate-50 rounded-t-2xl border-b border-slate-200 flex items-center gap-4">
                                <div
                                    class="w-12 h-12 flex-shrink-0 bg-gradient-to-br from-indigo-500 to-purple-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-800">Keamanan Akun</h3>
                                    <p class="text-sm text-slate-500 mt-1">Kosongkan jika tidak ingin mengubah password.
                                    </p>
                                </div>
                            </div>
                            <div class="p-6 md:p-8 space-y-6">
                                <div>
                                    <label for="password"
                                        class="block text-sm font-semibold text-gray-800 mb-2">Password Baru</label>
                                    <input type="password" id="password" name="password"
                                        autocomplete="new-password"
                                        class="block w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label for="password_confirmation"
                                        class="block text-sm font-semibold text-gray-800 mb-2">Konfirmasi Password
                                        Baru</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        autocomplete="new-password"
                                        class="block w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </div>

                        {{-- TOMBOL AKSI SIMPAN & BATAL --}}
                        <div class="flex justify-end items-center space-x-4 pt-4">
                            <a href="{{ route('anggota.profil') }}"
                                class="px-6 py-3 font-semibold text-slate-600 bg-white rounded-xl hover:bg-slate-100 border border-slate-300 transition-colors">Batal</a>

                            <button type="submit"
                                class="group flex items-center justify-center gap-3 px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-indigo-500/30">
                                <svg class="w-5 h-5 transition-transform group-hover:-rotate-12" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V3">
                                    </path>
                                </svg>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>

                    </div>
                </div>
            </form>
            {{-- AKHIR FORM UTAMA --}}
        </div>
    </section>

    <footer class="bg-slate-800 text-white py-6 mt-16">
        <div class="max-w-7xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <p class="text-slate-400">&copy; {{ date('Y') }} Perpustakaan Smpn 01 Keling. Semua Hak Dilindungi.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fotoInput = document.getElementById('foto-input');
            const fotoPreview = document.getElementById('foto-preview');
            const resetFotoBtn = document.getElementById('reset-foto-btn');
            const fileNameDisplay = document.getElementById('file-name-display');
            const originalSrc = fotoPreview.getAttribute('data-original-src');

            fotoInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    fotoPreview.src = URL.createObjectURL(file);
                    fileNameDisplay.textContent = file.name;
                }
            });

            resetFotoBtn.addEventListener('click', function() {
                fotoPreview.src = originalSrc;
                fotoInput.value = '';
                fileNameDisplay.textContent = '';
            });
        });
    </script>
</body>

</html>
