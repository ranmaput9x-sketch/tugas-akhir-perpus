<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DigiLib</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7fafc;
        }

        /* Animasi untuk ilustrasi (biar gerak naik turun) */
        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .animate-float {
            animation: float 5s ease-in-out infinite;
        }

        /* Animasi untuk form (muncul dari bawah) */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-container>* {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
        }

        /* Memberi delay untuk setiap elemen di form */
        .form-container>h2 {
            animation-delay: 0.1s;
        }

        .form-container>p:not(.signup-link) {
            animation-delay: 0.2s;
        }

        .form-container>div[role="alert"] {
            animation-delay: 0.2s;
        }

        .form-container>form {
            animation-delay: 0.3s;
        }

        .form-container>.signup-link {
            animation-delay: 0.8s;
        }
    </style>
</head>

<body>
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="flex w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden">

            <div
                class="hidden md:flex w-1/2 bg-gradient-to-br from-indigo-500 to-purple-600 p-12 flex-col items-center justify-center text-white text-center">
                <svg class="w-3/4 h-auto animate-float" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="8.5" cy="7" r="4" />
                    <polyline points="17 11 19 13 23 9" />
                </svg>
                <h2 class="text-3xl font-bold mt-8">Selamat Datang!</h2>
                <p class="mt-2 text-indigo-100">Temukan dunia pengetahuan di ujung jarimu bersama DigiLib.</p>
            </div>

            <div class="w-full md:w-1/2 p-8 md:p-12 form-container">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Masuk Akun</h2>
                <p class="text-gray-500 mb-6">Silakan masukkan detail akun Anda.</p>

                @error('email')
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md" role="alert">
                        <p><b>Oops!</b> {{ $message }}</p>
                    </div>
                @enderror
                @error('password')
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md" role="alert">
                        <p><b>Oops!</b> {{ $message }}</p>
                    </div>
                @enderror

                <form action="{{ route('anggota.login.process') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg
                                class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                </path>
                            </svg></span>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="block w-full pl-10 pr-3 py-3 text-gray-700 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:bg-white transition"
                            placeholder="Email Anda">
                    </div>

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg
                                class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg></span>
                        <input type="password" id="password" name="password" required
                            class="block w-full pl-10 pr-10 py-3 text-gray-700 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:bg-white transition"
                            placeholder="Kata Sandi">
                        <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-purple-600">
                            <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <svg id="eye-off-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 .847 0 1.67.127 2.454.364m3.033 2.158A9.986 9.986 0 0121.542 12c-1.274 4.057-5.064 7-9.542 7a10.05 10.05 0 01-1.425-.175M5.125 5.175l13.75 13.65m-13.75 0L18.875 5.175">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full px-4 py-3 text-white font-semibold bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg hover:from-purple-700 hover:to-indigo-700 focus:outline-none transform hover:scale-105 transition-transform duration-300">Masuk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const togglePasswordButton = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eye-icon');
        const eyeOffIcon = document.getElementById('eye-off-icon');

        togglePasswordButton.addEventListener('click', function() {
            // Cek tipe input password
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Ganti ikon mata
            eyeIcon.classList.toggle('hidden');
            eyeOffIcon.classList.toggle('hidden');
        });
    </script>
</body>

</html>
