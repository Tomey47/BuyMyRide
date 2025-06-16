@extends('layouts.app')

@section('title', $car->make . ' ' . $car->model)

@section('content')
<div 
    x-data="{ reportModalOpen: false }"
    class="container mx-auto px-2 sm:px-4 py-4 sm:py-8"
>
    <div class="flex flex-col md:flex-row gap-6 md:gap-8 mb-6">
        <!-- Breadcrumb -->
        <div class="flex-1 mb-4 md:mb-0">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-xs md:text-base">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-custom-darkyellow">
                            Sākums
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 md:w-6 md:h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('cars.index') }}" class="ml-1 text-gray-500 hover:text-custom-darkyellow md:ml-2">Auto sludinājumi</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 md:w-6 md:h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-500 md:ml-2">{{ $car->make }} {{ $car->model }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-6 md:gap-8">
        <!-- Image Section -->
        <div 
            x-data="{ 
                selected: '{{ $car->images->count() ? asset('storage/' . $car->images->first()->image_path) : '' }}', 
                showModal: false 
            }" 
            class="bg-white rounded-xl border border-gray-200 p-2 sm:p-4 flex flex-col items-center md:w-2/3 w-full"
        >
            @if($car->images->count())
                <img 
                    :src="selected" 
                    alt="{{ $car->make }} {{ $car->model }}" 
                    class="w-full max-h-72 sm:max-h-[410px] object-cover rounded mb-3 shadow transition-all duration-200 cursor-zoom-in"
                    @click="showModal = true"
                >
                <div class="flex gap-2 pb-2 overflow-x-auto w-full">
                    @foreach($car->images as $image)
                        <img 
                            src="{{ asset('storage/' . $image->image_path) }}" 
                            class="h-14 w-20 object-cover rounded border border-gray-200 shadow-sm cursor-pointer transition-all duration-150 hover:scale-105"
                            @click="selected = '{{ asset('storage/' . $image->image_path) }}'"
                            alt=""
                        >
                    @endforeach
                </div>

                <div 
                    x-show="showModal" 
                    x-transition 
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80"
                    @click.away="showModal = false"
                    @keydown.escape.window="showModal = false"
                >
                    <img :src="selected" class="max-h-[90vh] max-w-[90vw] rounded shadow-lg" alt="Pilnais attēls">
                    <button @click="showModal = false" class="absolute top-4 right-4 text-white text-3xl font-bold">&times;</button>
                </div>
            @else
                <div class="h-48 sm:h-[400px] w-full bg-gray-200 flex items-center justify-center rounded-lg text-gray-400">Nav attēla</div>
            @endif
        </div>

        <!-- Info and Description -->
        <div class="md:w-1/3 w-full flex flex-col gap-4 sm:gap-6 mt-4 md:mt-0">
            <!-- Info Card -->
            <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6 flex flex-col gap-3 sm:gap-4">
                <div class="flex justify-between items-start gap-2">
                    <h1 class="text-xl sm:text-3xl font-bold leading-tight">{{ $car->make }} {{ $car->model }} <span class="text-base sm:text-xl font-semibold text-gray-400">({{ $car->year }})</span></h1>
                    @auth
                        @php
                            $isFavorited = auth()->check() ? auth()->user()->favorites()->where('car_id', $car->id)->exists() : false;
                        @endphp
                        <a href="{{ route('favorites.toggle', $car) }}" 
                           class="favorite-btn w-10 h-10 flex items-center justify-center bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full shadow-md transition-all duration-200 border border-gray-200"
                           title="{{ $isFavorited ? 'Noņemt no favorītiem' : 'Pievienot favorītiem' }}"
                        >
                            @if($isFavorited)
                                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-gray-400 hover:text-red-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            @endif
                        </a>
                    @endauth
                </div>
                <div class="text-lg sm:text-2xl font-extrabold text-custom-darkyellow mb-1">{{ currency_symbol() }}{{ number_format(convert_currency($car->price), 2) }}</div>
                <div class="flex items-center text-gray-700 text-sm sm:text-base">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 21C12 21 5 13.5 5 9.5A7 7 0 0 1 19 9.5C19 13.5 12 21 12 21Z" />
                        <circle cx="12" cy="9.5" r="2.5" />
                    </svg>
                    {{ $car->location }}
                </div>
                <div class="mb-2 text-gray-600 text-xs sm:text-base">
                    <span class="font-semibold">Tips:</span> {{ $car->body_type ? __('car.body_types.' . strtolower(trim($car->body_type))) : '-' }}<br>
                    <span class="font-semibold">Pārnesumkārba:</span> {{ $car->transmission ? __('car.transmissions.' . strtolower(trim($car->transmission))) : '-' }}<br>
                    <span class="font-semibold">Degviela:</span> {{ $car->fuel_type ? __('car.fuel_types.' . strtolower(trim($car->fuel_type))) : '-' }}<br>
                    <span class="font-semibold">Krāsa:</span> {{ $car->color ?: '-' }}<br>
                    <span class="font-semibold">Nobraukums:</span> {{ $car->mileage ? number_format($car->mileage) . ' km' : '-' }}<br>
                </div>
            </div>
            <!-- Seller Card -->
            <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6 flex flex-col justify-center">
                <div class="flex justify-between items-start">
                    <h2 class="text-lg font-bold mb-4">Pārdevējs</h2>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('storage/' . ($car->user->avatar ?? '../images/default-profile.png')) }}" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <div class="flex items-start justify-between w-full">
                            <div>
                                <div class="font-semibold text-base sm:text-lg">{{ $car->user->username ?? 'Nezināms pārdevējs' }}</div>
                                @if($car->show_email)
                                    <div class="text-gray-600 text-xs sm:text-sm">{{ $car->user->email ?? 'Nav norādīts e-pasts' }}</div>
                                @endif
                                <div class="text-gray-600 text-xs sm:text-sm">
                                    @if(!empty($car->user->phone_number))
                                        {{ $car->user->country_code ? $car->user->country_code . ' ' : '' }}{{ $car->user->phone_number }}
                                    @else
                                        Nav norādīts tālrunis
                                    @endif
                                </div>
                                <div class="flex items-center gap-1 mt-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 {{ $i <= round($car->user->averageRating()) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118l-3.385-2.46c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                                    @endfor
                                    <span class="text-[10px] sm:text-xs text-gray-500 ml-1">({{ $car->user->totalRatings() }})</span>
                                </div>
                            </div>
                            @auth
                                @if(auth()->id() !== $car->user_id && !$car->is_reported)
                                    <div class="flex flex-col items-end ml-4">
                                        <span class="text-xs text-gray-500 mb-1">Novērtēt</span>
                                        <div class="flex items-center gap-1">
                                            @php
                                                $myRating = null;
                                                if(auth()->check()) {
                                                    $myRating = $car->user->ratingsReceived->where('user_id', auth()->id())->first();
                                                }
                                            @endphp
                                            @for ($i = 1; $i <= 5; $i++)
                                                <form method="POST" action="{{ route('ratings.store', $car->user) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" name="rating" value="{{ $i }}" class="focus:outline-none">
                                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ $myRating && $i <= $myRating->rating ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-500 transition" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118l-3.385-2.46c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                                                    </button>
                                                </form>
                                            @endfor
                                        </div>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-col md:flex-row gap-6 md:gap-8 mt-6">
        <!-- Description Card (under image, same width as image card) -->
        <div class="flex flex-col md:flex-row gap-4 md:gap-8 w-full">
            <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6 md:w-2/3 w-full mb-4 md:mb-0">
                <h2 class="text-lg sm:text-xl font-bold mb-2">Apraksts</h2>
                <div class="text-gray-700 whitespace-pre-line text-sm sm:text-base">
                    {{ $car->description ?: 'Nav apraksta.' }}
                </div>
            </div>
            <!-- Report/Date Card (to the right) -->
            <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6 md:w-1/3 w-full flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold mb-2">Sludinājuma informācija</h2>
                        <div class="mb-2 text-xs sm:text-base">
                            <span class="font-semibold text-gray-600">Publicēts:</span>
                            <span class="text-gray-600">{{ $car->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                    </div>
                    <div>
                        @auth
                            @if(auth()->id() !== $car->user_id && !$car->is_reported)
                                <button 
                                    @click="reportModalOpen = true"
                                    type="button"
                                    class="px-3 py-1.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition text-xs sm:text-sm border border-red-200 cursor-pointer ml-2"
                                >Sūdzēties</button>
                            @elseif($car->is_reported && auth()->id() !== $car->user_id)
                                <span class="px-3 py-1.5 bg-red-50 text-red-500 rounded-lg text-xs sm:text-sm border border-red-100 ml-2 inline-block">Nosūdzēts</span>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Specs Grid -->
    <div class="mt-6 sm:mt-8 bg-white rounded-xl border border-gray-200 p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-bold mb-4">Tehniskie dati</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 text-gray-700 text-xs sm:text-base">
            <div>
                <div class="font-semibold">Tips</div>
                <div>{{ $car->body_type ? __('car.body_types.' . strtolower(trim($car->body_type))) : '-' }}</div>
            </div>
            <div>
                <div class="font-semibold">Pārnesumkārba</div>
                <div>{{ $car->transmission ? __('car.transmissions.' . strtolower(trim($car->transmission))) : '-' }}</div>
            </div>
            <div>
                <div class="font-semibold">Degviela</div>
                <div>{{ $car->fuel_type ? __('car.fuel_types.' . strtolower(trim($car->fuel_type))) : '-' }}</div>
            </div>
            <div>
                <div class="font-semibold">Krāsa</div>
                <div>{{ $car->color ?: '-' }}</div>
            </div>
            <div>
                <div class="font-semibold">Nobraukums</div>
                <div>{{ $car->mileage ? number_format($car->mileage) . ' km' : '-' }}</div>
            </div>
            <div>
                <div class="font-semibold">Gads</div>
                <div>{{ $car->year ?: '-' }}</div>
            </div>
        </div>
    </div>

    <!-- Report Modal -->
    <div 
        x-show="reportModalOpen"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center"
        style="background: rgba(0,0,0,0.4);"
    >
        <div 
            @click.away="reportModalOpen = false"
            class="bg-white rounded-xl border border-gray-200 w-full max-w-md mx-2 p-4 sm:p-6 shadow-lg overflow-y-auto max-h-[90vh]"
        >
            <h2 class="text-xl font-bold mb-4">Sūdzēties par sludinājumu</h2>
            <p class="text-gray-600 mb-6">Vai tiešām vēlaties sūdzēties par šo sludinājumu?</p>
            <div class="flex justify-end gap-2">
                <button
                    @click="reportModalOpen = false"
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-custom-gray cursor-pointer w-full sm:w-auto"
                >
                    Atcelt
                </button>
                <form method="POST" action="{{ route('cars.report', $car) }}" class="inline">
                    @csrf
                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 cursor-pointer w-full sm:w-auto"
                    >
                        Sūdzēties
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection