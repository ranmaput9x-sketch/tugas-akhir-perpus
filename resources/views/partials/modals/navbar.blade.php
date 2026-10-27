<nav
    class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/70 backdrop-blur supports-[backdrop-filter]:bg-white/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-20 flex items-center justify-between">

            {{-- Left: Brand --}}
            <a href="{{ route('katalog.index') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo_sekolah.png') }}" alt="DigiLib logo"
                    class="h-10 w-10 rounded-full border border-slate-200 shadow-sm">
                <div class="leading-tight">
                    <p class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-indigo-700 transition">
                        Perpustakaan Smpn 01 Keling
                    </p>
                    <p class="text-xs text-slate-500 hidden sm:block">DigiLib • Perpustakaan Digital</p>
                </div>
            </a>

            {{-- Center: Desktop Nav --}}
            @php
                $links = [
                    ['label' => 'Beranda', 'route' => 'katalog.index'],
                    ['label' => 'Daftar Buku', 'route' => 'katalog.daftar-buku'],
                    ['label' => 'Tentang', 'route' => 'katalog.tentang'],
                ];
            @endphp

            <div class="hidden md:flex items-center gap-2">
                @foreach ($links as $l)
                    <a href="{{ route($l['route']) }}"
                        class="px-4 py-2 rounded-xl text-sm font-semibold transition
                        {{ request()->routeIs($l['route'])
                            ? 'bg-indigo-600 text-white shadow-sm'
                            : 'text-slate-600 hover:text-indigo-700 hover:bg-slate-100' }}">
                        {{ $l['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- Right: Actions --}}
            <div class="hidden md:flex items-center gap-3">
                {{-- Search shortcut (optional) --}}
                <a href="{{ route('katalog.daftar-buku') }}"
                    class="p-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 hover:text-indigo-700 transition"
                    aria-label="Cari Buku">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </a>

                @if (Auth::guard('anggota')->check())
                    @php
                        $anggota = Auth::guard('anggota')->user();
                        $initial = strtoupper(mb_substr($anggota->nama_anggota ?? 'A', 0, 1));
                    @endphp

                    <div class="relative">
                        <button type="button" data-dd-trigger="profile"
                            class="inline-flex items-center gap-3 pl-3 pr-2 py-2 rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition focus:outline-none focus:ring-4 focus:ring-indigo-200"
                            aria-haspopup="true" aria-expanded="false">
                            <span class="text-sm text-slate-700">
                                Halo, <span class="font-bold">{{ Str::words($anggota->nama_anggota, 1, '') }}</span>
                            </span>
                            <span
                                class="h-9 w-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold">
                                {{ $initial }}
                            </span>
                        </button>

                        <div data-dd-menu="profile"
                            class="hidden absolute right-0 mt-3 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">
                            <div class="p-3 border-b border-slate-100">
                                <p class="text-sm font-semibold text-slate-900 truncate">{{ $anggota->nama_anggota }}
                                </p>
                                <p class="text-xs text-slate-500 truncate">Anggota</p>
                            </div>

                            <a href="{{ route('anggota.profil') }}"
                                class="flex items-center gap-2 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path d="M10 10a4 4 0 100-8 4 4 0 000 8zM2 18a8 8 0 1116 0H2z" />
                                </svg>
                                Profil
                            </a>

                            <form method="POST" action="{{ route('anggota.logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left flex items-center gap-2 px-4 py-3 text-sm font-semibold text-rose-700 hover:bg-rose-50 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M3 4.5A1.5 1.5 0 014.5 3h6A1.5 1.5 0 0112 4.5V7a1 1 0 11-2 0V5H5v10h5v-2a1 1 0 112 0v2.5A1.5 1.5 0 0110.5 18h-6A1.5 1.5 0 013 16.5v-12z"
                                            clip-rule="evenodd" />
                                        <path fill-rule="evenodd"
                                            d="M16.707 10.707a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 9H9a1 1 0 100 2h4.586l-1.293 1.293a1 1 0 101.414 1.414l3-3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('anggota.login.form') }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition focus:outline-none focus:ring-4 focus:ring-indigo-200">
                        Masuk Anggota
                    </a>
                @endif
            </div>

            {{-- Mobile: Hamburger --}}
            <button type="button" data-mobile-trigger
                class="md:hidden inline-flex items-center justify-center p-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 transition"
                aria-label="Buka menu" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M3 5h14a1 1 0 010 2H3a1 1 0 010-2zm0 4h14a1 1 0 010 2H3a1 1 0 010-2zm0 4h14a1 1 0 010 2H3a1 1 0 010-2z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div data-mobile-menu class="md:hidden hidden border-t border-slate-200/70 bg-white/80 backdrop-blur">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 space-y-2">

            @foreach ($links as $l)
                <a href="{{ route($l['route']) }}"
                    class="block px-4 py-3 rounded-2xl text-sm font-semibold transition
                    {{ request()->routeIs($l['route']) ? 'bg-indigo-600 text-white' : 'text-slate-700 hover:bg-slate-50' }}">
                    {{ $l['label'] }}
                </a>
            @endforeach

            <div class="pt-3 border-t border-slate-200 space-y-2">
                <a href="{{ route('katalog.daftar-buku') }}"
                    class="block px-4 py-3 rounded-2xl text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                    Cari Buku
                </a>

                @if (Auth::guard('anggota')->check())
                    <a href="{{ route('anggota.profil') }}"
                        class="block px-4 py-3 rounded-2xl text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Profil
                    </a>

                    <form method="POST" action="{{ route('anggota.logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-3 rounded-2xl text-sm font-semibold text-rose-700 hover:bg-rose-50 transition">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('anggota.login.form') }}"
                        class="block text-center px-4 py-3 rounded-2xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                        Masuk Anggota
                    </a>
                @endif
            </div>
        </div>
    </div>
</nav>

<script>
    (function() {
        // Mobile menu toggle
        const mobileTrigger = document.querySelector('[data-mobile-trigger]');
        const mobileMenu = document.querySelector('[data-mobile-menu]');

        if (mobileTrigger && mobileMenu) {
            mobileTrigger.addEventListener('click', () => {
                const isHidden = mobileMenu.classList.contains('hidden');
                mobileMenu.classList.toggle('hidden');
                mobileTrigger.setAttribute('aria-expanded', String(isHidden));
            });
        }

        // Dropdown profile toggle (desktop)
        const ddTrigger = document.querySelector('[data-dd-trigger="profile"]');
        const ddMenu = document.querySelector('[data-dd-menu="profile"]');

        function closeDropdown() {
            if (!ddMenu || !ddTrigger) return;
            ddMenu.classList.add('hidden');
            ddTrigger.setAttribute('aria-expanded', 'false');
        }

        if (ddTrigger && ddMenu) {
            ddTrigger.addEventListener('click', (e) => {
                e.stopPropagation();
                const willOpen = ddMenu.classList.contains('hidden');
                closeDropdown();
                if (willOpen) {
                    ddMenu.classList.remove('hidden');
                    ddTrigger.setAttribute('aria-expanded', 'true');
                }
            });

            document.addEventListener('click', closeDropdown);
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeDropdown();
            });
        }
    })();
</script>
