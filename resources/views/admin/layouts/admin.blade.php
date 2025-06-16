<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <script src="//unpkg.com/alpinejs" defer></script>
    <title>Administrācijas Panelis - BuyMyRide</title>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-custom-black">
                                <img src="{{ asset('images/logo1.png') }}" alt="Logo" class="h-10 w-auto">
                            </a>
                        </div>

                        <div class="hidden sm:ml-10 sm:flex sm:space-x-8">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-custom-black border-b-2 border-custom-darkyellow' : 'text-gray-500 hover:text-custom-black hover:border-custom-lightyellow border-b-2 border-transparent' }}">
                                Vadības Panelis
                            </a>
                            <a href="{{ route('admin.listings.index') }}" 
                               class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('admin.listings.*') ? 'text-custom-black border-b-2 border-custom-darkyellow' : 'text-gray-500 hover:text-custom-black hover:border-custom-lightyellow border-b-2 border-transparent' }}">
                                Sludinājumi
                            </a>
                            <a href="{{ route('admin.users') }}" 
                               class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('admin.users') ? 'text-custom-black border-b-2 border-custom-darkyellow' : 'text-gray-500 hover:text-custom-black hover:border-custom-lightyellow border-b-2 border-transparent' }}">
                                Lietotāji
                            </a>
                        </div>
                    </div>

                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <a href="{{ route('home') }}" class="mr-4 px-3 py-2 text-sm font-medium text-gray-500 hover:text-custom-black cursor-pointer flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                            </svg>
                            Iziet no Vadības Paneļa
                        </a>

                        <div class="relative ml-3" x-data="{ open: false }">
                            <button @click="open = !open" class="relative p-2 text-custom-black hover:text-gray-500 cursor-pointer">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if(auth()->user()->unreadNotifications()->count() > 0)
                                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
                                @endif
                            </button>

                            <div x-show="open" 
                                 @click.away="open = false"
                                 class="origin-top-right absolute right-0 mt-2 w-80 rounded-md bg-white border border-gray-200 ring-1 ring-black ring-opacity-5 z-50"
                                 style="display: none;">
                                <div class="py-1">
                                    <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-200">
                                        <h3 class="font-semibold">Paziņojumi</h3>
                                    </div>
                                    @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                        <a href="{{ $notification->car_id ? route('admin.listings.pending') : '#' }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-b border-gray-100 last:border-b-0">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    @if($notification->type === 'car_created')
                                                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                        </svg>
                                                    @else
                                                        <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm">{{ $notification->message }}</p>
                                                    <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="px-4 py-2 text-sm text-gray-500">
                                            Nav jaunu paziņojumu
                                        </div>
                                    @endforelse
                                    @if(auth()->user()->unreadNotifications()->count() > 0)
                                        <form action="{{ route('admin.notifications.markAllAsRead') }}" method="POST" class="border-t border-gray-200">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                                                Atzīmēt visus kā izlasītus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="ml-3">
                            @csrf
                            <button type="submit" class="px-3 py-2 text-sm font-medium text-gray-500 hover:text-custom-black cursor-pointer">
                                Iziet
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
</body>
</html> 