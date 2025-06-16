@extends('admin.layouts.admin')

@section('content')
<div class="bg-white overflow-hidden border border-gray-200 sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <h1 class="text-2xl font-semibold mb-6">Administratora Panelis</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Users Card -->
            <div class="bg-white overflow-hidden border border-gray-200 rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Kopējais Lietotāju Skaits</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ \App\Models\User::count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Listings Card -->
            <div class="bg-white overflow-hidden border border-gray-200 rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Apstiprinātie Sludinājumi</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ \App\Models\Car::where('is_approved', true)->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Listings Card -->
            <div class="bg-white overflow-hidden border border-gray-200 rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Gaida Apstiprināšanu</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ \App\Models\Car::where('is_approved', false)->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users Table -->
        <div class="mt-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Pēdējie Lietotāji</h2>
            <div class="bg-white overflow-hidden border border-gray-200 sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach(\App\Models\User::latest()->take(5)->get() as $user)
                    <li>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-profile.png') }}" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $user->username }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500">
                                    Pievienojās {{ $user->created_at->format('d.m.Y') }}
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <!-- System Monitoring Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <!-- Server Uptime -->
            <div class="bg-white overflow-hidden border border-gray-200 rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Servera darbības laiks</dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ gmdate('H:i:s', time() - @file_get_contents('/proc/uptime')) ?? 'N/A' }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <!-- PHP Version -->
            <div class="bg-white overflow-hidden border border-gray-200 rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m4 0h-1v4h-1m4-4h-1v4h-1" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">PHP Versija</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ PHP_VERSION }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Disk Usage -->
            <div class="bg-white overflow-hidden border border-gray-200 rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17v-2a4 4 0 014-4h10a4 4 0 014 4v2" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Diska Lietojums</dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ round((disk_total_space("/")-disk_free_space("/"))/1024/1024/1024, 2) }} GB /
                                    {{ round(disk_total_space("/")/1024/1024/1024, 2) }} GB
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 