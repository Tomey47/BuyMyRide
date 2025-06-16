<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <script src="//unpkg.com/alpinejs" defer></script>
    <title>@yield('title', 'BuyMyRide')</title>
</head>
<body class="bg-gray-50">
    <div 
        x-data="{ 
            show: false,
            message: '',
            mobileMenuOpen: false,
            init() {
                @if(session('success') && !request()->routeIs('show.login'))
                    this.show = true;
                    this.message = '{{ session('success') }}';
                    setTimeout(() => this.show = false, 3000);
                @endif
            }
        }"
        class="min-h-screen flex flex-col"
    >
        <header class="w-full bg-white border-b border-gray-200">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between py-4">
                    <!-- Logo and Admin Panel -->
                    <div class="flex items-center gap-4">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('images/logo1.png') }}" alt="Logo" class="h-12 w-auto">
                        </a>
                        @auth
                            @if(auth()->user()->is_admin)
                                <div class="hidden md:block pl-8">
                                    <a href="{{ route('admin.dashboard') }}" class="text-custom-gray hover:text-black cursor-pointer flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                        </svg>
                                        Vadības Panelis
                                    </a>
                                </div>
                            @endif
                        @endauth
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <!-- Desktop Navigation -->
                    <nav class="hidden md:flex items-center space-x-6">
                        <a href="{{ url('/cars') }}" class="text-custom-gray hover:text-black">Katalogs</a>
                        <a href="{{ url('/about') }}" class="text-custom-gray hover:text-black">Par Mums</a>
                        @guest
                            <a href="{{ route('show.login') }}" class="bg-custom-darkyellow text-custom-gray px-6 py-2 rounded-md font-semibold no-underline transition-all duration-200 shadow-[0_2px_8px_rgba(0,0,0,0.05)] hover:bg-custom-lightyellow hover:text-custom-black">Pieslēgties</a>
                        @endguest
                        @auth
                            <div class="relative">
                                <a href="{{ route('favorites.index') }}" class="text-custom-gray hover:text-black">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    @php
                                        $favoritesCount = auth()->check() ? auth()->user()->favorites()->count() : 0;
                                    @endphp
                                    @if($favoritesCount > 0)
                                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                            {{ $favoritesCount > 99 ? '99+' : $favoritesCount }}
                                        </span>
                                    @endif
                                </a>
                            </div>
                            <div class="border-r border-gray-400 pr-6">
                                <a href="{{ route('profile') }}" class="text-custom-gray hover:text-black">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </a>
                            </div>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button class="cursor-pointer text-custom-lightgray hover:text-black">Iziet</button>
                            </form>
                        @endauth
                        <form action="{{ route('currency.switch') }}" method="POST" class="inline">
                            @csrf
                            <select name="currency" onchange="this.form.submit()" class="bg-white border rounded px-2 py-1">
                                <option value="EUR" {{ session('currency', 'EUR') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="USD" {{ session('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="GBP" {{ session('currency') == 'GBP' ? 'selected' : '' }}>GBP</option>
                            </select>
                        </form>
                    </nav>
                </div>

                <!-- Mobile Navigation Menu -->
                <div 
                    x-show="mobileMenuOpen" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-4"
                    class="md:hidden py-4 border-t border-gray-200"
                >
                    <nav class="flex flex-col space-y-4">
                        <a href="{{ url('/cars') }}" class="text-custom-gray hover:text-black">Katalogs</a>
                        <a href="{{ url('/about') }}" class="text-custom-gray hover:text-black">Par Mums</a>
                        @guest
                            <a href="{{ route('show.login') }}" class="bg-custom-darkyellow text-custom-gray px-6 py-2 rounded-md font-semibold no-underline transition-all duration-500 shadow-[0_2px_8px_rgba(0,0,0,0.05)] hover:bg-custom-lightyellow hover:text-custom-black">Pieslēgties</a>
                        @endguest
                        @auth
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('favorites.index') }}" class="text-custom-gray hover:text-black flex items-center">
                                    <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    Favorīti
                                    @if($favoritesCount > 0)
                                        <span class="ml-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                            {{ $favoritesCount > 99 ? '99+' : $favoritesCount }}
                                        </span>
                                    @endif
                                </a>
                            </div>
                            <a href="{{ route('profile') }}" class="text-custom-gray hover:text-black flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Profils
                            </a>
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="text-custom-gray hover:text-black flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                    </svg>
                                    Vadības Panelis
                                </a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button class="cursor-pointer text-custom-lightgray hover:text-black">Iziet</button>
                            </form>
                        @endauth
                        <form action="{{ route('currency.switch') }}" method="POST" class="inline">
                            @csrf
                            <select name="currency" onchange="this.form.submit()" class="bg-white border rounded px-2 py-1 w-full">
                                <option value="EUR" {{ session('currency', 'EUR') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="USD" {{ session('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="GBP" {{ session('currency') == 'GBP' ? 'selected' : '' }}>GBP</option>
                            </select>
                        </form>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Flash Message -->
        <div 
            x-show="show" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-4"
            class="fixed left-1/2 top-10 -translate-x-1/2 z-50 w-full max-w-md md:max-w-lg px-2 md:px-0"
        >
            <div class="bg-white border-2 border-custom-lightyellow shadow-[0_0_15px_rgba(234,179,8,0.4)] text-black px-4 py-3 md:px-6 md:py-4 rounded-lg relative animate-pulse-subtle w-full text-center">
                <span x-text="message" class="text-base md:text-lg font-medium"></span>
            </div>
        </div>

        <main class="flex-1 bg-gray-50">
            @yield('content')
        </main>
        <footer class="w-full bg-custom-black py-6 rounded-t shadow">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="text-white font-semibold text-center md:text-left">
                        &copy; {{ date('Y') }} BuyMyRide. Visas tiesības aizsargātas.
                    </div>
                    <nav class="flex flex-wrap gap-6 justify-center md:justify-end">
                        <a href="{{ url('/about') }}" class="text-white hover:text-custom-darkyellow transition-colors">Par Mums</a>
                        <a href="{{ url('/contact') }}" class="text-white hover:text-custom-darkyellow transition-colors">Kontakti</a>
                    </nav>
                </div>
            </div>
        </footer>
    </div>

    <style>
        @keyframes pulse-subtle {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
        .animate-pulse-subtle {
            animation: pulse-subtle 2s infinite;
        }

        /* Mobile-specific styles */
        @media (max-width: 768px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .btn {
                width: 100%;
                text-align: center;
                padding: 0.75rem 1rem;
                margin-top: 0.5rem;
            }
            .animate-pulse-subtle {
                font-size: 1rem;
                padding: 0.75rem 1rem;
            }
        }
    </style>
</body>
</html>