@extends('layouts.app')

@section('title', 'Mani favorīti')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-custom-black mb-2">Mani favorīti</h1>
            <p class="text-gray-600">Jūsu saglabātie auto sludinājumi</p>
        </div>

        @if($favorites->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($favorites as $car)
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <!-- Image -->
                        <div class="relative h-48 overflow-hidden">
                            @if($car->images->count() > 0)
                                <img src="{{ asset('storage/' . $car->images->first()->image_path) }}" 
                                     alt="{{ $car->make }} {{ $car->model }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                                    Nav attēla
                                </div>
                            @endif
                            
                            <!-- Favorite Button -->
                            <div class="absolute top-3 right-3">
                                <a href="{{ route('favorites.toggle', $car) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full shadow-md transition-all duration-200 border border-gray-200"
                                   title="Noņemt no favorītiem"
                                >
                                    <svg class="w-5 h-5 text-red-500 fill-current" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-semibold text-custom-black truncate">
                                    {{ $car->make }} {{ $car->model }}
                                </h3>
                                <div class="text-xl font-bold text-custom-darkyellow">
                                    €{{ number_format($car->price, 2) }}
                                </div>
                            </div>
                            
                            <div class="text-gray-600 text-sm mb-3">
                                <p>{{ $car->year }} • {{ $car->body_type ? __('car.body_types.' . strtolower(trim($car->body_type))) : '-' }} • {{ $car->transmission ? __('car.transmissions.' . strtolower(trim($car->transmission))) : '-' }}</p>
                                <p>{{ $car->mileage ? number_format($car->mileage) . ' km' : '-' }} • {{ $car->location }}</p>
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('cars.show', $car) }}" 
                                   class="flex-1 bg-custom-darkyellow text-white text-center py-2 px-4 rounded-lg hover:bg-custom-lightyellow transition font-semibold text-sm">
                                    Skatīt
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $favorites->links('pagination::custom') }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-4 text-gray-300">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Nav favorītu</h3>
                <p class="text-gray-600 mb-6">Jums vēl nav saglabāts neviens auto sludinājums</p>
                <a href="{{ route('cars.index') }}" 
                   class="inline-block bg-custom-darkyellow text-white px-6 py-3 rounded-lg hover:bg-custom-lightyellow transition font-semibold">
                    Pārlūkot sludinājumus
                </a>
            </div>
        @endif
    </div>
</div>
@endsection 