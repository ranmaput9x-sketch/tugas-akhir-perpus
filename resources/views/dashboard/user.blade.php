<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Library Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        .glassmorphism {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .book-cover {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .book-cover:hover {
            transform: translateY(-5px) scale(1.03);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .scroll-smooth {
            scroll-behavior: smooth;
        }

        .highlight-card {
            background: linear-gradient(135deg, rgba(101, 114, 255, 0.1), rgba(234, 92, 255, 0.1));
        }
    </style>
</head>

<body
    class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-screen scroll-smooth font-sans transition-colors duration-300">
    <div class="flex min-h-screen">
        <!-- Sidebar Navigation -->
        <div
            class="glassmorphism w-64 min-h-screen p-6 hidden md:block fixed left-0 top-0 z-10 bg-blue-800/10 dark:bg-gray-800/30">
            <div class="flex items-center mb-10">
                <img src="https://placehold.co/40x40" alt="Library logo - modern geometric book icon"
                    class="rounded-lg mr-3">
                <h1 class="text-xl font-bold">Digital Library</h1>
            </div>

            <div class="mb-8">
                <div class="flex items-center mb-6">
                    <div
                        class="h-10 w-10 rounded-full bg-blue-200 dark:bg-blue-600 flex items-center justify-center mr-3 overflow-hidden">
                        <img src="https://placehold.co/100x100" alt="User profile picture - professional portrait"
                            class="h-full w-full object-cover">
                    </div>
                    <div>
                        <p class="font-medium">Welcome back,</p>
                        <p class="font-semibold">John Doe</p>
                    </div>
                </div>
                <p class="text-sm opacity-80">Student ID: STU2023001</p>
                <p class="text-sm opacity-80">Faculty of Computer Science</p>
            </div>

            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="#dashboard"
                            class="flex items-center p-3 rounded-lg hover:bg-blue-100/30 dark:hover:bg-gray-700/50 transition-all">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="#catalog"
                            class="flex items-center p-3 rounded-lg hover:bg-blue-100/30 dark:hover:bg-gray-700/50 transition-all">
                            <i class="fas fa-book-open mr-3"></i>
                            Book Catalog
                        </a>
                    </li>
                    <li>
                        <a href="#borrowed"
                            class="flex items-center p-3 rounded-lg hover:bg-blue-100/30 dark:hover:bg-gray-700/50 transition-all">
                            <i class="fas fa-bookmark mr-3"></i>
                            My Borrowings
                        </a>
                    </li>
                    <li>
                        <a href="#history"
                            class="flex items-center p-3 rounded-lg hover:bg-blue-100/30 dark:hover:bg-gray-700/50 transition-all">
                            <i class="fas fa-history mr-3"></i>
                            Borrow History
                        </a>
                    </li>
                    <li>
                        <a href="#profile"
                            class="flex items-center p-3 rounded-lg hover:bg-blue-100/30 dark:hover:bg-gray-700/50 transition-all">
                            <i class="fas fa-user-circle mr-3"></i>
                            My Profile
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="mt-auto pt-6 border-t border-gray-200/30 dark:border-gray-700/50">
                <button
                    class="flex items-center justify-between w-full p-3 rounded-lg bg-blue-100/30 dark:bg-gray-700/30 hover:bg-blue-200/40 dark:hover:bg-gray-600/40 transition-all">
                    <div class="flex items-center">
                        <i class="fas fa-moon mr-3"></i>
                        <span>Dark Mode</span>
                    </div>
                    <div
                        class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                        <input type="checkbox" id="toggle"
                            class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                        <label for="toggle"
                            class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                    </div>
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 md:ml-64">
            <!-- Mobile Header -->
            <header class="md:hidden glassmorphism p-4 flex justify-between items-center sticky top-0 z-10">
                <button id="menuToggle" class="text-xl">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="text-xl font-bold">Library Dashboard</h1>
                <div class="h-8 w-8 rounded-full bg-blue-200 dark:bg-blue-600 overflow-hidden">
                    <img src="https://placehold.co/100x100" alt="User profile picture thumbnail"
                        class="h-full w-full object-cover">
                </div>
            </header>

            <main class="p-6">
                <!-- Welcome Banner -->
                <section id="dashboard" class="animate-fade-in mb-12">
                    <div class="highlight-card rounded-2xl p-8 mb-6 relative overflow-hidden">
                        <div class="absolute -right-10 -bottom-10 h-40 w-40 bg-blue-400/20 rounded-full filter blur-xl">
                        </div>
                        <div class="absolute -left-10 -top-10 h-40 w-40 bg-purple-400/20 rounded-full filter blur-xl">
                        </div>
                        <div class="relative z-10">
                            <h1 class="text-3xl font-bold mb-2">Welcome back, John!</h1>
                            <p class="text-lg mb-6 max-w-2xl">Ready to continue your learning journey? Check out new
                                arrivals or return to your current readings.</p>
                            <div class="flex flex-wrap gap-4">
                                <div
                                    class="flex items-center bg-white/20 dark:bg-gray-800/40 backdrop-blur-md rounded-lg px-4 py-3">
                                    <div class="bg-blue-500 text-white p-3 rounded-lg mr-3">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm opacity18 AOT compiler (Angular 9+)
                                
