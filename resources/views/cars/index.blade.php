@extends('layouts.app')

@section('title', 'Auto sludinājumi')

@section('content')
<div x-data="{ filtersOpen: false }" class="container mx-auto px-4 py-4 md:py-8">
    <!-- Breadcrumb and Sort Section -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
        <!-- Breadcrumb -->
        <div class="flex-1">
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
                    @if(request('make') || request('model') || request('year') || request('body_type') || request('transmission') || request('fuel_type') || request('price_min') || request('price_max') || request('mileage') || request('location'))
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 md:w-6 md:h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-gray-500 md:ml-2">Filtrētie rezultāti</span>
                            </div>
                        </li>
                    @endif
                </ol>
            </nav>
        </div>

        <!-- Sort By -->
        <div class="w-full md:w-auto">
            <form method="GET" action="{{ route('cars.index') }}" class="flex items-center gap-2">
                @foreach(request()->except('sort') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                
                <label for="sort" class="text-sm font-medium text-gray-700 whitespace-nowrap">Kārtot pēc:</label>
                <select name="sort" id="sort" onchange="this.form.submit()" 
                    class="w-full md:w-auto px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition text-sm font-medium">
                    <option value="">Noklusējuma</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Cena: augošā secībā</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Cena: dilstošā secībā</option>
                    <option value="year_desc" {{ request('sort') == 'year_desc' ? 'selected' : '' }}>Gads: jaunākie sākumā</option>
                    <option value="year_asc" {{ request('sort') == 'year_asc' ? 'selected' : '' }}>Gads: vecākie sākumā</option>
                    <option value="mileage_asc" {{ request('sort') == 'mileage_asc' ? 'selected' : '' }}>Nobraukums: augošā secībā</option>
                    <option value="mileage_desc" {{ request('sort') == 'mileage_desc' ? 'selected' : '' }}>Nobraukums: dilstošā secībā</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Filters Toggle Button (Mobile Only) -->
    <button @click="filtersOpen = !filtersOpen" class="block sm:hidden mb-4 bg-custom-darkyellow text-white px-4 py-2 rounded-lg font-semibold shadow-sm w-full">
        <span x-show="!filtersOpen">Rādīt filtrus</span>
        <span x-show="filtersOpen">Paslēpt filtrus</span>
    </button>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Filters Section -->
        <aside class="w-full sm:w-80 lg:w-80 xl:w-96">
            <!-- Mobile: Collapsible -->
            <div x-show="filtersOpen" x-transition class="block sm:hidden mb-6">
                <form method="GET" action="{{ route('cars.index') }}" class="bg-white border border-gray-200 rounded-lg p-4 md:p-6">
                    <h2 class="text-xl font-semibold mb-6 text-gray-800">Meklēšanas filtri</h2>
                    
                    <!-- Basic Information -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Pamatinformācija</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 mb-1" for="make_mobile">Marka</label>
                                <select id="make_mobile" name="make" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition">
                                    <option value="">Jebkura marka</option>
                                    <option value="Abarth" {{ request('make') == 'Abarth' ? 'selected' : '' }}>Abarth</option>
                                    <option value="Alfa Romeo" {{ request('make') == 'Alfa Romeo' ? 'selected' : '' }}>Alfa Romeo</option>
                                    <option value="Aston Martin" {{ request('make') == 'Aston Martin' ? 'selected' : '' }}>Aston Martin</option>
                                    <option value="Audi" {{ request('make') == 'Audi' ? 'selected' : '' }}>Audi</option>
                                    <option value="BMW" {{ request('make') == 'BMW' ? 'selected' : '' }}>BMW</option>
                                    <option value="Chevrolet" {{ request('make') == 'Chevrolet' ? 'selected' : '' }}>Chevrolet</option>
                                    <option value="Citroën" {{ request('make') == 'Citroën' ? 'selected' : '' }}>Citroën</option>
                                    <option value="Dacia" {{ request('make') == 'Dacia' ? 'selected' : '' }}>Dacia</option>
                                    <option value="Fiat" {{ request('make') == 'Fiat' ? 'selected' : '' }}>Fiat</option>
                                    <option value="Ford" {{ request('make') == 'Ford' ? 'selected' : '' }}>Ford</option>
                                    <option value="Honda" {{ request('make') == 'Honda' ? 'selected' : '' }}>Honda</option>
                                    <option value="Hyundai" {{ request('make') == 'Hyundai' ? 'selected' : '' }}>Hyundai</option>
                                    <option value="Kia" {{ request('make') == 'Kia' ? 'selected' : '' }}>Kia</option>
                                    <option value="Lexus" {{ request('make') == 'Lexus' ? 'selected' : '' }}>Lexus</option>
                                    <option value="Mazda" {{ request('make') == 'Mazda' ? 'selected' : '' }}>Mazda</option>
                                    <option value="Mercedes-Benz" {{ request('make') == 'Mercedes-Benz' ? 'selected' : '' }}>Mercedes-Benz</option>
                                    <option value="Nissan" {{ request('make') == 'Nissan' ? 'selected' : '' }}>Nissan</option>
                                    <option value="Opel" {{ request('make') == 'Opel' ? 'selected' : '' }}>Opel</option>
                                    <option value="Peugeot" {{ request('make') == 'Peugeot' ? 'selected' : '' }}>Peugeot</option>
                                    <option value="Renault" {{ request('make') == 'Renault' ? 'selected' : '' }}>Renault</option>
                                    <option value="Seat" {{ request('make') == 'Seat' ? 'selected' : '' }}>Seat</option>
                                    <option value="Skoda" {{ request('make') == 'Skoda' ? 'selected' : '' }}>Skoda</option>
                                    <option value="Toyota" {{ request('make') == 'Toyota' ? 'selected' : '' }}>Toyota</option>
                                    <option value="Volkswagen" {{ request('make') == 'Volkswagen' ? 'selected' : '' }}>Volkswagen</option>
                                    <option value="Volvo" {{ request('make') == 'Volvo' ? 'selected' : '' }}>Volvo</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-1" for="model_mobile">Modelis</label>
                                <select id="model_mobile" name="model" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition">
                                    <option value="">Jebkurš modelis</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-1" for="year">Gads</label>
                                <select id="year" name="year" 
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition">
                                    <option value="">Jebkurš gads</option>
                                    @for($i = date('Y'); $i >= 1990; $i--)
                                        <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Cenas diapazons (€)</h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-2">
                                <input type="number" name="price_min" id="price_min" min="0" placeholder="Min" 
                                    value="{{ request('price_min', 0) }}" 
                                    class="w-1/2 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition">
                                <span class="text-gray-500">-</span>
                                <input type="number" name="price_max" id="price_max" min="0" placeholder="Maks" 
                                    value="{{ request('price_max') }}" 
                                    class="w-1/2 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition">
                            </div>
                            <div class="px-2">
                                <input type="range" min="0" max="100000" step="500" value="{{ request('price_min', 0) }}" 
                                    id="slider_min" class="w-full" 
                                    oninput="document.getElementById('price_min').value = this.value">
                                <input type="range" min="0" max="100000" step="500" value="{{ request('price_max', 100000) }}" 
                                    id="slider_max" class="w-full" 
                                    oninput="document.getElementById('price_max').value = this.value">
                                <div class="flex justify-between text-xs text-gray-400 mt-1">
                                    <span>€0</span>
                                    <span>€100,000+</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Details -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Auto detaļas</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 mb-1" for="body_type">Virsbūves tips</label>
                                <select id="body_type" name="body_type" 
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition">
                                    <option value="">Jebkurš virsbūves tips</option>
                                    <option value="sedan" {{ request('body_type') == 'sedan' ? 'selected' : '' }}>Sedans</option>
                                    <option value="SUV" {{ request('body_type') == 'SUV' ? 'selected' : '' }}>Apvidus auto</option>
                                    <option value="hatchback" {{ request('body_type') == 'hatchback' ? 'selected' : '' }}>Hečbeks</option>
                                    <option value="coupe" {{ request('body_type') == 'coupe' ? 'selected' : '' }}>Kupeja</option>
                                    <option value="wagon" {{ request('body_type') == 'wagon' ? 'selected' : '' }}>Universālis</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-1" for="transmission">Pārnesumkārba</label>
                                <select id="transmission" name="transmission" 
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition">
                                    <option value="">Jebkura pārnesumkārba</option>
                                    <option value="automatic" {{ request('transmission') == 'automatic' ? 'selected' : '' }}>Automātiskā</option>
                                    <option value="manual" {{ request('transmission') == 'manual' ? 'selected' : '' }}>Manuālā</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-1" for="fuel_type">Degviela</label>
                                <select id="fuel_type" name="fuel_type" 
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition">
                                    <option value="">Jebkura degviela</option>
                                    <option value="petrol" {{ request('fuel_type') == 'petrol' ? 'selected' : '' }}>Benzīns</option>
                                    <option value="diesel" {{ request('fuel_type') == 'diesel' ? 'selected' : '' }}>Dīzelis</option>
                                    <option value="electric" {{ request('fuel_type') == 'electric' ? 'selected' : '' }}>Elektriskais</option>
                                    <option value="hybrid" {{ request('fuel_type') == 'hybrid' ? 'selected' : '' }}>Hibrīds</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-1" for="mileage">Maksimālais nobraukums (km)</label>
                                <input type="number" id="mileage" name="mileage" value="{{ request('mileage') }}" 
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition">
                            </div>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Atrašanās vieta</h3>
                        <div>
                            <label class="block text-gray-700 mb-1" for="location">Pilsēta/reģions</label>
                            <input type="text" id="location" name="location" value="{{ request('location') }}" 
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition">
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 bg-custom-darkyellow text-white py-2 rounded-lg hover:bg-yellow-600 transition cursor-pointer">
                            Pielietot filtrus
                        </button>
                        <a href="{{ route('cars.index') }}" class="flex-1 bg-gray-100 text-gray-700 py-2 rounded-lg hover:bg-gray-200 transition text-center cursor-pointer">
                            Atiestatīt
                        </a>
                    </div>
                </form>
            </div>
            <!-- Desktop: Always visible -->
            <div class="hidden sm:block">
                <form method="GET" action="{{ route('cars.index') }}" class="bg-white border border-gray-200 rounded-lg p-4 md:p-6 mb-6">
                <h2 class="text-xl font-semibold mb-6 text-gray-800">Meklēšanas filtri</h2>
                
                <!-- Basic Information -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Pamatinformācija</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 mb-1" for="make_desktop">Marka</label>
                            <select id="make_desktop" name="make" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition">
                                <option value="">Jebkura marka</option>
                                <option value="Abarth" {{ request('make') == 'Abarth' ? 'selected' : '' }}>Abarth</option>
                                <option value="Alfa Romeo" {{ request('make') == 'Alfa Romeo' ? 'selected' : '' }}>Alfa Romeo</option>
                                <option value="Aston Martin" {{ request('make') == 'Aston Martin' ? 'selected' : '' }}>Aston Martin</option>
                                <option value="Audi" {{ request('make') == 'Audi' ? 'selected' : '' }}>Audi</option>
                                <option value="BMW" {{ request('make') == 'BMW' ? 'selected' : '' }}>BMW</option>
                                <option value="Chevrolet" {{ request('make') == 'Chevrolet' ? 'selected' : '' }}>Chevrolet</option>
                                <option value="Citroën" {{ request('make') == 'Citroën' ? 'selected' : '' }}>Citroën</option>
                                <option value="Dacia" {{ request('make') == 'Dacia' ? 'selected' : '' }}>Dacia</option>
                                <option value="Fiat" {{ request('make') == 'Fiat' ? 'selected' : '' }}>Fiat</option>
                                <option value="Ford" {{ request('make') == 'Ford' ? 'selected' : '' }}>Ford</option>
                                <option value="Honda" {{ request('make') == 'Honda' ? 'selected' : '' }}>Honda</option>
                                <option value="Hyundai" {{ request('make') == 'Hyundai' ? 'selected' : '' }}>Hyundai</option>
                                <option value="Kia" {{ request('make') == 'Kia' ? 'selected' : '' }}>Kia</option>
                                <option value="Lexus" {{ request('make') == 'Lexus' ? 'selected' : '' }}>Lexus</option>
                                <option value="Mazda" {{ request('make') == 'Mazda' ? 'selected' : '' }}>Mazda</option>
                                <option value="Mercedes-Benz" {{ request('make') == 'Mercedes-Benz' ? 'selected' : '' }}>Mercedes-Benz</option>
                                <option value="Nissan" {{ request('make') == 'Nissan' ? 'selected' : '' }}>Nissan</option>
                                <option value="Opel" {{ request('make') == 'Opel' ? 'selected' : '' }}>Opel</option>
                                <option value="Peugeot" {{ request('make') == 'Peugeot' ? 'selected' : '' }}>Peugeot</option>
                                <option value="Renault" {{ request('make') == 'Renault' ? 'selected' : '' }}>Renault</option>
                                <option value="Seat" {{ request('make') == 'Seat' ? 'selected' : '' }}>Seat</option>
                                <option value="Skoda" {{ request('make') == 'Skoda' ? 'selected' : '' }}>Skoda</option>
                                <option value="Toyota" {{ request('make') == 'Toyota' ? 'selected' : '' }}>Toyota</option>
                                <option value="Volkswagen" {{ request('make') == 'Volkswagen' ? 'selected' : '' }}>Volkswagen</option>
                                <option value="Volvo" {{ request('make') == 'Volvo' ? 'selected' : '' }}>Volvo</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1" for="model_desktop">Modelis</label>
                            <select id="model_desktop" name="model" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition">
                                <option value="">Jebkurš modelis</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1" for="year">Gads</label>
                            <select id="year" name="year" 
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition">
                                <option value="">Jebkurš gads</option>
                                @for($i = date('Y'); $i >= 1990; $i--)
                                    <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Price Range -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Cenas diapazons (€)</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <input type="number" name="price_min" id="price_min" min="0" placeholder="Min" 
                                value="{{ request('price_min', 0) }}" 
                                class="w-1/2 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition">
                            <span class="text-gray-500">-</span>
                            <input type="number" name="price_max" id="price_max" min="0" placeholder="Maks" 
                                value="{{ request('price_max') }}" 
                                class="w-1/2 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition">
                        </div>
                        <div class="px-2">
                            <input type="range" min="0" max="100000" step="500" value="{{ request('price_min', 0) }}" 
                                id="slider_min" class="w-full" 
                                oninput="document.getElementById('price_min').value = this.value">
                            <input type="range" min="0" max="100000" step="500" value="{{ request('price_max', 100000) }}" 
                                id="slider_max" class="w-full" 
                                oninput="document.getElementById('price_max').value = this.value">
                            <div class="flex justify-between text-xs text-gray-400 mt-1">
                                <span>€0</span>
                                <span>€100,000+</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Details -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Auto detaļas</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 mb-1" for="body_type">Virsbūves tips</label>
                            <select id="body_type" name="body_type" 
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition">
                                <option value="">Jebkurš virsbūves tips</option>
                                <option value="sedan" {{ request('body_type') == 'sedan' ? 'selected' : '' }}>Sedans</option>
                                <option value="SUV" {{ request('body_type') == 'SUV' ? 'selected' : '' }}>Apvidus auto</option>
                                <option value="hatchback" {{ request('body_type') == 'hatchback' ? 'selected' : '' }}>Hečbeks</option>
                                <option value="coupe" {{ request('body_type') == 'coupe' ? 'selected' : '' }}>Kupeja</option>
                                <option value="wagon" {{ request('body_type') == 'wagon' ? 'selected' : '' }}>Universālis</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1" for="transmission">Pārnesumkārba</label>
                            <select id="transmission" name="transmission" 
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition">
                                <option value="">Jebkura pārnesumkārba</option>
                                <option value="automatic" {{ request('transmission') == 'automatic' ? 'selected' : '' }}>Automātiskā</option>
                                <option value="manual" {{ request('transmission') == 'manual' ? 'selected' : '' }}>Manuālā</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1" for="fuel_type">Degviela</label>
                            <select id="fuel_type" name="fuel_type" 
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition">
                                <option value="">Jebkura degviela</option>
                                <option value="petrol" {{ request('fuel_type') == 'petrol' ? 'selected' : '' }}>Benzīns</option>
                                <option value="diesel" {{ request('fuel_type') == 'diesel' ? 'selected' : '' }}>Dīzelis</option>
                                <option value="electric" {{ request('fuel_type') == 'electric' ? 'selected' : '' }}>Elektriskais</option>
                                <option value="hybrid" {{ request('fuel_type') == 'hybrid' ? 'selected' : '' }}>Hibrīds</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1" for="mileage">Maksimālais nobraukums (km)</label>
                            <input type="number" id="mileage" name="mileage" value="{{ request('mileage') }}" 
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition">
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Atrašanās vieta</h3>
                    <div>
                        <label class="block text-gray-700 mb-1" for="location">Pilsēta/reģions</label>
                        <input type="text" id="location" name="location" value="{{ request('location') }}" 
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-custom-darkyellow text-white py-2 rounded-lg hover:bg-yellow-600 transition cursor-pointer">
                        Pielietot filtrus
                    </button>
                    <a href="{{ route('cars.index') }}" class="flex-1 bg-gray-100 text-gray-700 py-2 rounded-lg hover:bg-gray-200 transition text-center cursor-pointer">
                        Atiestatīt
                    </a>
                </div>
            </form>
            </div>
        </aside>

        <!-- Cars Grid -->
        <div class="flex-1">
            <div class="flex flex-col gap-3">
                @forelse($cars as $car)
                    <a href="{{ route('cars.show', $car) }}" class="block">
                        <div class="bg-white rounded border border-gray-200 flex flex-col sm:flex-row w-full py-2 sm:py-1 relative hover:shadow-md transition">
                            <div class="pl-3 flex items-center justify-center sm:justify-start relative">
                            @if($car->images->count())
                                    <img src="{{ asset('storage/' . $car->images->first()->image_path) }}" alt="{{ $car->make }} {{ $car->model }}" class="h-24 sm:h-32 w-full sm:w-48 object-cover rounded">
                            @else
                                    <div class="h-24 sm:h-32 w-full sm:w-48 bg-gray-200 flex items-center justify-center rounded text-gray-400">Nav attēla</div>
                            @endif
                                @auth
                                    @php
                                        $isFavorited = auth()->check() ? auth()->user()->favorites()->where('car_id', $car->id)->exists() : false;
                                    @endphp
                                    <!-- Mobile: Favorite icon over image, top-left -->
                                    <div class="absolute top-2 left-2 sm:hidden z-10">
                                    <a href="{{ route('favorites.toggle', $car) }}" 
                                           class="favorite-btn w-10 h-10 flex items-center justify-center bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full shadow-md transition-all duration-200 border border-gray-200"
                                           title="{{ $isFavorited ? 'Noņemt no favorītiem' : 'Pievienot favorītiem' }}">
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
                                    </div>
                                @endauth
                            </div>
                            <div class="flex-1 flex items-center">
                                @auth
                                    <!-- Desktop: Favorite icon on the right -->
                                    <div class="hidden sm:flex items-center justify-center p-2 sm:order-last sm:pr-4">
                                        <a href="{{ route('favorites.toggle', $car) }}"
                                           class="favorite-btn w-10 h-10 flex items-center justify-center bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full shadow-md transition-all duration-200 border border-gray-200"
                                           title="{{ $isFavorited ? 'Noņemt no favorītiem' : 'Pievienot favorītiem' }}">
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
                                    </div>
                                @endauth
                                <div class="flex-1 p-2 sm:pl-4">
                                    <div class="mb-1 font-semibold text-base sm:text-lg">{{ $car->make }} {{ $car->model }} ({{ $car->year }})</div>
                                    <div class="mb-1 text-gray-600 text-xs sm:text-sm">
                                        {{ $car->body_type ? __('car.body_types.' . strtolower(trim($car->body_type))) : '-' }},
                                        {{ $car->transmission ? __('car.transmissions.' . strtolower(trim($car->transmission))) : '-' }},
                                        {{ $car->fuel_type ? __('car.fuel_types.' . strtolower(trim($car->fuel_type))) : '-' }}
                                    </div>
                                    <div class="mb-1 text-gray-600 text-xs sm:text-sm">Nobraukums: {{ $car->mileage ? number_format($car->mileage) . ' km' : '-' }}</div>
                                    <div class="mb-1 text-base sm:text-lg font-bold text-custom-darkyellow">{{ currency_symbol() }}{{ number_format(convert_currency($car->price), 2) }}</div>
                                    <div class="mb-1 text-gray-500 text-xs flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 21C12 21 5 13.5 5 9.5A7 7 0 0 1 19 9.5C19 13.5 12 21 12 21Z" />
                                            <circle cx="12" cy="9.5" r="2.5" />
                                        </svg>
                                        {{ $car->location ?: '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="bg-white rounded-lg border border-gray-200 p-8 text-center">
                        <p class="text-gray-500">Nav atrasts neviens auto sludinājums.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('price_min').addEventListener('input', function() {
        document.getElementById('slider_min').value = this.value;
    });
    document.getElementById('price_max').addEventListener('input', function() {
        document.getElementById('slider_max').value = this.value;
    });

    const models = {
        'Abarth': ['500', '595', '124 Spider', 'Punto'],
        'Alfa Romeo': ['Giulia', 'Stelvio', '4C', 'Giulietta', 'Mito'],
        'Aston Martin': ['DB11', 'Vantage', 'DBS', 'DBX', 'Valkyrie'],
        'Audi': ['A3', 'A4', 'A6', 'A8', 'Q3', 'Q5', 'Q7', 'Q8', 'e-tron', 'TT', 'R8'],
        'BMW': ['1 Series', '2 Series', '3 Series', '4 Series', '5 Series', '6 Series', '7 Series', '8 Series', 'X1', 'X3', 'X5', 'X7', 'M3', 'M5', 'Z4'],
        'Chevrolet': ['Camaro', 'Corvette', 'Malibu', 'Silverado', 'Tahoe'],
        'Citroën': ['C3', 'C4', 'C5', 'Berlingo', 'C3 Aircross'],
        'Dacia': ['Duster', 'Sandero', 'Logan', 'Spring'],
        'Fiat': ['500', 'Panda', 'Tipo', '500X', 'Ducato'],
        'Ford': ['Fiesta', 'Focus', 'Mustang', 'Explorer', 'F-150', 'Ranger', 'Kuga', 'Puma'],
        'Honda': ['Civic', 'Accord', 'CR-V', 'Jazz', 'HR-V', 'NSX'],
        'Hyundai': ['i10', 'i20', 'i30', 'Tucson', 'Santa Fe', 'Kona', 'IONIQ', 'Palisade'],
        'Kia': ['Picanto', 'Rio', 'Ceed', 'Sportage', 'Sorento', 'Stinger', 'EV6'],
        'Lexus': ['IS', 'ES', 'LS', 'NX', 'RX', 'GX', 'LX', 'UX', 'LC', 'RC'],
        'Mazda': ['2', '3', '6', 'CX-3', 'CX-5', 'CX-9', 'MX-5'],
        'Mercedes-Benz': ['A-Class', 'C-Class', 'E-Class', 'S-Class', 'GLA', 'GLC', 'GLE', 'GLS', 'AMG GT', 'CLA', 'CLS'],
        'Nissan': ['Micra', 'Note', 'Qashqai', 'X-Trail', 'Juke', 'Leaf', '370Z', 'GT-R'],
        'Opel': ['Corsa', 'Astra', 'Insignia', 'Mokka', 'Crossland', 'Grandland'],
        'Peugeot': ['208', '308', '508', '2008', '3008', '5008'],
        'Renault': ['Clio', 'Megane', 'Captur', 'Kadjar', 'Twingo', 'Zoe'],
        'Seat': ['Ibiza', 'Leon', 'Arona', 'Ateca', 'Tarraco'],
        'Skoda': ['Fabia', 'Octavia', 'Superb', 'Kodiaq', 'Karoq', 'Enyaq'],
        'Toyota': ['Aygo', 'Yaris', 'Corolla', 'Camry', 'RAV4', 'Highlander', 'Land Cruiser', 'Supra'],
        'Volkswagen': ['Polo', 'Golf', 'Passat', 'Tiguan', 'T-Roc', 'Touareg', 'ID.3', 'ID.4', 'ID.5'],
        'Volvo': ['XC40', 'XC60', 'XC90', 'S60', 'S90', 'V60', 'V90']
    };

    function setupMakeModel(makeId, modelId, selectedModel) {
        const makeSelect = document.getElementById(makeId);
        const modelSelect = document.getElementById(modelId);
        if (!makeSelect || !modelSelect) return;
    makeSelect.addEventListener('change', function() {
        const selectedMake = this.value;
            modelSelect.innerHTML = '<option value="">Jebkurš modelis</option>';
        if (selectedMake && models[selectedMake]) {
            models[selectedMake].forEach(model => {
                const option = document.createElement('option');
                option.value = model;
                option.textContent = model;
                    if (model === selectedModel) {
                    option.selected = true;
                }
                modelSelect.appendChild(option);
            });
        }
    });
        if (makeSelect.value) {
            makeSelect.dispatchEvent(new Event('change'));
        }
    }
    setupMakeModel('make_mobile', 'model_mobile', '{{ request('model') }}');
    setupMakeModel('make_desktop', 'model_desktop', '{{ request('model') }}');
</script>
@endsection