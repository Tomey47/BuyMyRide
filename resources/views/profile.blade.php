@extends('layouts.app')

@section('title', 'Profils')

@section('content')
<div 
    x-data="profilePageData(
        {{ $errors->any() && !$errors->avatar->any() && !$errors->addCar->any() ? 'true' : 'false' }},
        {{ $errors->addCar->any() ? 'true' : 'false' }},
        {{ $errors->avatar->any() ? 'true' : 'false' }}
    )"
    class="min-h-screen bg-gray-50 py-8"
>
    <div class="max-w-6xl mx-auto px-4">
        <!-- Profile Header -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden w-full">
            <div class="relative h-25 bg-gradient-to-r from-custom-darkyellow to-custom-lightyellow flex flex-col items-center sm:block">
                <div class="absolute -bottom-16 left-1/2 transform -translate-x-1/2 sm:static sm:translate-x-0 sm:left-8 z-10">
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-lg cursor-pointer relative group" @click="avatarOpen = true">
                        <img src="{{ asset('storage/' . ($user->avatar ?? '../images/default-profile.png')) }}" alt="Profila foto" class="object-cover w-full h-full">
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-80 transition-opacity duration-200">
                            <img src="{{ asset('images/change-picture.png') }}" alt="Change picture" class="w-32 h-32">
                        </div>
                    </div>
                </div>
            </div>
            <div class="pt-20 pb-6 px-4 sm:px-8 relative text-center sm:text-left">
                <div class="absolute top-4 right-4 sm:right-6">
                    <button @click="open = true" type="button" class="bg-custom-darkyellow text-white px-4 sm:px-5 py-2 rounded-lg hover:bg-custom-lightyellow transition font-semibold text-sm shadow-sm cursor-pointer">Rediģēt profilu</button>
                </div>
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-custom-black">{{ auth()->user()->username }}</h2>
                    <p class="text-gray-500 mt-1 text-sm sm:text-base">{{ auth()->user()->email }}</p>
                    <p class="text-gray-500 mt-1 text-sm sm:text-base">
                        {{ auth()->user()->phone_number ? (auth()->user()->country_code . ' ' . auth()->user()->phone_number) : 'Nav norādīts tālrunis' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Info -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Konta informācija</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide">Lietotājvārds</div>
                            <div class="font-medium text-custom-black mt-1 break-all">{{ auth()->user()->username }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide">E-pasts</div>
                            <div class="font-medium text-custom-black mt-1 break-all">{{ auth()->user()->email }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide">Tālruņa numurs</div>
                            <div class="font-medium text-custom-black mt-1">{{ auth()->user()->phone_number ? (auth()->user()->country_code . ' ' . auth()->user()->phone_number) : 'Nav norādīts tālrunis' }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide">Pievienojies</div>
                            <div class="font-medium text-custom-black mt-1">
                                @php
                                    \Carbon\Carbon::setLocale('lv');
                                @endphp
                                {{ auth()->user()->created_at->translatedFormat('F Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Listings -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-gray-200">
                    <div class="border-b border-gray-200 px-4 sm:px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-2">
                        <h3 class="text-lg font-semibold text-gray-900">Mani sludinājumi</h3>
                        <button @click="openAddCar()" type="button" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-semibold text-sm shadow-sm cursor-pointer w-full sm:w-auto">Pievienot auto</button>
                    </div>
                    <div class="p-4 sm:p-6">
                        @if($cars->count() > 0)
                            <div class="space-y-4">
                                @foreach($cars as $car)
                                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-3 sm:p-4 flex flex-col sm:flex-row gap-3 sm:gap-4 w-full">
                                        <!-- Image -->
                                        <div class="w-full sm:w-40 h-40 sm:h-32 rounded-lg overflow-hidden flex-shrink-0 mx-auto sm:mx-0">
                                            @if($car->images->count() > 0)
                                                <img src="{{ asset('storage/' . $car->images->first()->image_path) }}" alt="{{ $car->make }} {{ $car->model }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">Nav attēla</div>
                                            @endif
                                        </div>
                                        <!-- Info -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                                                <div>
                                                    <h3 class="text-base sm:text-lg font-semibold truncate flex items-center gap-2">{{ $car->make }} {{ $car->model }}
                                                        @if($car->status === 'draft')
                                                            <span class="ml-2 px-2 py-0.5 rounded bg-yellow-200 text-yellow-800 text-xs font-semibold">Melnraksts</span>
                                                        @endif
                                                    </h3>
                                                    <p class="text-gray-600 text-xs sm:text-base">{{ $car->year }} • {{ $car->body_type ? __('car.body_types.' . strtolower(trim($car->body_type))) : '-' }} • {{ $car->transmission ? __('car.transmissions.' . strtolower(trim($car->transmission))) : '-' }}</p>
                                                    <p class="text-gray-600 text-xs sm:text-base">{{ $car->mileage ? number_format($car->mileage) . ' km' : '-' }} • {{ $car->location }}</p>
                                                </div>
                                                <div class="text-right flex-shrink-0 ml-0 sm:ml-4">
                                                    <div class="text-lg sm:text-xl font-bold text-custom-darkyellow">€{{ number_format($car->price, 2) }}</div>
                                                    <div class="text-xs sm:text-sm text-gray-500">{{ $car->created_at->format('d.m.Y') }}</div>
                                                </div>
                                            </div>
                                            <!-- Actions -->
                                            <div class="mt-3 flex flex-col sm:flex-row gap-2">
                                                @if($car->status === 'published' && $car->is_approved == 1)
                                                    <a href="{{ route('cars.show', $car) }}" class="px-3 py-2 bg-white text-gray-700 rounded-lg hover:bg-gray-100 transition text-sm border border-gray-200 cursor-pointer w-full sm:w-auto text-center">Skatīt</a>
                                                @endif
                                                <button 
                                                    @click="openEditCar({{ json_encode($car) }})"
                                                    class="px-3 py-2 bg-custom-darkyellow text-white rounded-lg hover:bg-custom-lightyellow transition text-sm cursor-pointer w-full sm:w-auto text-center"
                                                >
                                                    Rediģēt
                                                </button>
                                                <button 
                                                    @click="carToDelete = {{ $car->id }}; deleteModalOpen = true" 
                                                    class="px-3 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition text-sm border border-red-100 cursor-pointer w-full sm:w-auto text-center"
                                                >
                                                    Dzēst
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-6">
                                {{ $cars->links('pagination::custom') }}
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500">Jums vēl nav pievienots neviens sludinājums</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Delete Account Section -->
        <div class="mt-6">
            <div class="bg-white rounded-2xl border border-red-200 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row items-center gap-3 mb-4 text-center sm:text-left">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mx-auto sm:mx-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-red-800">Dzēst kontu</h3>
                </div>
                <p class="text-gray-600 mb-4 text-sm">Šī darbība ir neatgriezeniska. Visi jūsu dati, sludinājumi un faili tiks neatgriezeniski dzēsti.</p>
                <button 
                    @click="deleteAccountOpen = true" 
                    type="button" 
                    class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition font-semibold text-sm shadow-sm cursor-pointer w-full sm:w-auto flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Dzēst kontu
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center" style="background: rgba(0,0,0,0.4);">
        <div @click.away="open = false" class="bg-white rounded-xl border border-gray-200 w-full max-w-md p-6 shadow-lg">
            <h2 class="text-xl font-bold mb-4">Rediģēt profilu</h2>
            @if ($errors->editCar->any())
                <div class="mb-4 rounded px-4 py-2 bg-red-100">
                    <ul>
                        @foreach ($errors->editCar->all() as $error)
                            <li class="text-red-600">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <!-- Profile Information Form -->
            <form method="POST" action="{{ route('profile.update') }}" class="mb-6">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 mb-1">Lietotājvārds</label>
                    <input type="text" name="username" value="{{ auth()->user()->username }}" class="block w-full mb-4 px-4 py-2 border rounded focus:outline-none text-gray-400 focus:text-custom-gray">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-1">E-pasts</label>
                    <input type="email" name="email" value="{{ auth()->user()->email }}" class="block w-full mb-4 px-4 py-2 border rounded focus:outline-none text-gray-400 focus:text-custom-gray">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-1">Tālruņa numurs</label>
                    <div class="flex gap-2 items-center">
                        <div class="relative w-40">
                            <select 
                                id="country_code" 
                                name="country_code"
                                class="w-full block px-2 py-2 border border-gray-400 rounded focus:outline-none text-gray-400 focus:text-custom-gray appearance-none bg-gray-100 cursor-pointer"
                                size="1"
                            >
                                @foreach($countryCodes as $country)
                                    <option 
                                        value="{{ $country['code'] }}" 
                                        {{ auth()->user()->country_code == $country['code'] ? 'selected' : '' }}
                                    >
                                        {{ $country['code'] }} {{ $country['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="pointer-events-none absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400">&#9662;</span>
                        </div>
                        <input 
                            type="text" 
                            name="phone_number" 
                            value="{{ auth()->user()->phone_number }}" 
                            class="block w-full px-4 py-2 border rounded focus:outline-none text-gray-400 focus:text-custom-gray"
                            placeholder="Tālruņa numurs"
                        >
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="open = false" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-custom-gray cursor-pointer">Atcelt</button>
                    <button type="submit" class="px-4 py-2 rounded bg-custom-darkyellow text-custom-black hover:bg-custom-lightyellow cursor-pointer">Saglabāt</button>
                </div>
            </form>

            <!-- Password Change Form -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold mb-4">Mainīt paroli</h3>
                <p class="text-sm text-gray-600 mb-4">Jaunajai parolei jābūt vismaz 8 rakstzīmes garai, saturēt lielos un mazos burtus, ciparus un simbolus.</p>
                <form method="POST" action="{{ route('profile.change-password') }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Pašreizējā parole</label>
                        <input type="password" name="current_password" class="block w-full mb-4 px-4 py-2 border rounded focus:outline-none text-gray-400 focus:text-custom-gray @error('current_password') border-red-500 @enderror">
                        @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Jaunā parole</label>
                        <input type="password" name="password" class="block w-full mb-4 px-4 py-2 border rounded focus:outline-none text-gray-400 focus:text-custom-gray @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Apstiprināt jauno paroli</label>
                        <input type="password" name="password_confirmation" class="block w-full mb-4 px-4 py-2 border rounded focus:outline-none text-gray-400 focus:text-custom-gray @error('password_confirmation') border-red-500 @enderror">
                        @error('password_confirmation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 cursor-pointer">Mainīt paroli</button>
                    </div>
                </form>
            </div>

            @if ($errors->any() && !$errors->addCar->any() && !$errors->editCar->any())
                <ul class="rounded px-4 py-2 mt-4 bg-red-100">
                    @foreach ($errors->all() as $error)
                        <li class="my-2 text-red-500">{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Add Car Modal -->
    <div x-show="addCarOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center" style="background: rgba(0,0,0,0.4);">
        <div class="relative">
            <!-- <div class="absolute right-full mr-4 rounded-xl px-4 py-3 bg-red-50 border border-red-200 shadow-sm w-120" x-show="false">
                <ul class="space-y-1.5">
                </ul>
            </div> -->
            @if ($errors->addCar->any())
                <div class="mb-4 rounded px-4 py-2 bg-red-100">
                    <ul>
                        @foreach ($errors->addCar->all() as $error)
                            <li class="text-red-600">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div @click.away="closeAddCarModal" class="bg-white rounded-xl border border-gray-200 w-full max-w-4xl p-6 shadow-lg">
                <h2 class="text-xl font-bold mb-4">Pievienot jaunu auto</h2>
                <form method="POST" action="{{ route('cars.store') }}" enctype="multipart/form-data" id="addCarForm">
                    @csrf
                    <div class="grid grid-cols-3 gap-4 mb-3">
                        <div class="mb-2">
                            <label class="block text-gray-700 mb-1" for="make">Marka <span class="text-red-500">*</span></label>
                            <select name="make" id="make" class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('addCar.make') border-red-500 @enderror">
                                <option value="">Izvēlieties marku</option>
                                <option value="Abarth">Abarth</option>
                                <option value="Alfa Romeo">Alfa Romeo</option>
                                <option value="Aston Martin">Aston Martin</option>
                                <option value="Audi">Audi</option>
                                <option value="BMW">BMW</option>
                                <option value="Chevrolet">Chevrolet</option>
                                <option value="Citroën">Citroën</option>
                                <option value="Dacia">Dacia</option>
                                <option value="Fiat">Fiat</option>
                                <option value="Ford">Ford</option>
                                <option value="Honda">Honda</option>
                                <option value="Hyundai">Hyundai</option>
                                <option value="Kia">Kia</option>
                                <option value="Lexus">Lexus</option>
                                <option value="Mazda">Mazda</option>
                                <option value="Mercedes-Benz">Mercedes-Benz</option>
                                <option value="Nissan">Nissan</option>
                                <option value="Opel">Opel</option>
                                <option value="Peugeot">Peugeot</option>
                                <option value="Renault">Renault</option>
                                <option value="Seat">Seat</option>
                                <option value="Skoda">Skoda</option>
                                <option value="Toyota">Toyota</option>
                                <option value="Volkswagen">Volkswagen</option>
                                <option value="Volvo">Volvo</option>
                            </select>
                            @error('addCar.make')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-700 mb-1" for="model">Modelis <span class="text-red-500">*</span></label>
                            <select name="model" id="model" class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('addCar.model') border-red-500 @enderror">
                                <option value="">Izvēlieties modeli</option>
                            </select>
                            @error('addCar.model')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-700 mb-1" for="year">Gads <span class="text-red-500">*</span></label>
                            <input type="number" id="year" name="year" min="1900" max="{{ date('Y') + 1 }}" 
                                class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('addCar.year') border-red-500 @enderror" placeholder="piem. 2018">
                            @error('addCar.year')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-700 mb-1" for="body_type">Tips <span class="text-red-500">*</span></label>
                            <select id="body_type" name="body_type" class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('addCar.body_type') border-red-500 @enderror">
                                <option value="">Izvēlieties</option>
                                <option value="sedan">Sedans</option>
                                <option value="SUV">Apvidus auto</option>
                                <option value="hatchback">Hečbeks</option>
                                <option value="coupe">Kupeja</option>
                                <option value="wagon">Universālis</option>
                                <option value="van">Furgons</option>
                                <option value="pickup">Pikaps</option>
                            </select>
                            @error('addCar.body_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-700 mb-1" for="transmission">Pārnesumkārba <span class="text-red-500">*</span></label>
                            <select id="transmission" name="transmission" class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('addCar.transmission') border-red-500 @enderror">
                                <option value="">Izvēlieties</option>
                                <option value="manual">Mehāniskā</option>
                                <option value="automatic">Automātiskā</option>
                            </select>
                            @error('addCar.transmission')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-700 mb-1" for="fuel_type">Degviela <span class="text-red-500">*</span></label>
                            <select id="fuel_type" name="fuel_type" class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('addCar.fuel_type') border-red-500 @enderror">
                                <option value="">Izvēlieties</option>
                                <option value="petrol">Benzīns</option>
                                <option value="diesel">Dīzelis</option>
                                <option value="electric">Elektriskais</option>
                                <option value="hybrid">Hibrīds</option>
                            </select>
                            @error('addCar.fuel_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-700 mb-1" for="mileage">Nobraukums (km)</label>
                            <input type="number" id="mileage" name="mileage" min="0" class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('addCar.mileage') border-red-500 @enderror" placeholder="piem. 50000">
                            @error('addCar.mileage')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-700 mb-1" for="color">Krāsa</label>
                            <input type="text" id="color" name="color" class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('addCar.color') border-red-500 @enderror" placeholder="piem. Melna">
                            @error('addCar.color')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-700 mb-1" for="price">Cena (€) <span class="text-red-500">*</span></label>
                            <input type="number" id="price" name="price" step="0.01" min="0" class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('addCar.price') border-red-500 @enderror" placeholder="piem. 15000">
                            @error('addCar.price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-700 mb-1" for="location">Atrašanās vieta <span class="text-red-500">*</span></label>
                            <input type="text" id="location" name="location" class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('addCar.location') border-red-500 @enderror" placeholder="piem. Rīga">
                            @error('addCar.location')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-gray-700 mb-1" for="description">Apraksts</label>
                        <textarea id="description" name="description" class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('addCar.description') border-red-500 @enderror" rows="2" placeholder="Aprakstiet savu auto..."></textarea>
                        @error('addCar.description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="show_email" name="show_email" value="1" class="rounded border-gray-300 text-custom-darkyellow focus:ring-custom-darkyellow">
                            <label for="show_email" class="text-gray-700">Rādīt manu e-pastu sludinājumā</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="block text-gray-700 mb-1" for="images">Attēli (Varat pievienot līdz 6 attēliem.) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="file" id="images" name="images[]" multiple="multiple" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" class="hidden @error('addCar.images') border-red-500 @enderror" @change="updateCount">
                            <div class="flex items-center gap-2">
                                <label for="images" class="block w-40 px-2 py-2 bg-gray-100 text-gray-700 rounded cursor-pointer hover:bg-gray-200 transition text-center">
                                    Pievienot attēlus
                                </label>
                                <span x-show="errorMessage" x-text="errorMessage" class="text-sm text-red-500"></span>
                            </div>
                            @error('addCar.images')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            @error('addCar.images.*')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <span x-show="selectedFiles > 0" x-text="'Izvēlēti ' + selectedFiles + ' attēli'" class="text-sm text-gray-500 ml-2"></span>
                            
                            <div x-show="previews.length > 0" class="grid grid-cols-6 gap-2">
                                <template x-for="(preview, index) in previews" :key="index">
                                    <div class="relative w-full">
                                        <img :src="preview" class="w-full h-15 object-cover rounded">
                                    </div>
                                </template>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Atbalstītie formāti: JPEG, PNG, JPG, GIF, WEBP. Maksimālais faila izmērs: 4MB</p>
                    </div>
                    
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="closeAddCarModal" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-custom-gray cursor-pointer">Atcelt</button>
                        <button type="button" onclick="document.getElementById('addCarForm').status.value='draft'; document.getElementById('addCarForm').submit();" class="px-4 py-2 rounded bg-yellow-200 text-yellow-800 hover:bg-yellow-300 cursor-pointer">Saglabāt kā melnrakstu</button>
                        <button type="submit" onclick="document.getElementById('addCarForm').status.value='published';" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700 cursor-pointer">Pievienot auto</button>
                    </div>
                    <input type="hidden" name="status" value="published">
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Avatar Modal -->
    <div x-show="avatarOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center" style="background: rgba(0,0,0,0.4);">
        <div @click.away="avatarOpen = false" class="bg-white rounded-xl border border-gray-200 w-full max-w-md p-6 shadow-lg">
            <h2 class="text-xl font-bold mb-4">Rediģēt profila foto</h2>
            <form method="POST" action="{{ route('profile.update.avatar') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <div class="relative">
                        <input type="file" name="avatar" accept="image/*" class="hidden" id="avatar_image" @change="updateAvatarFileName($event)">
                        <div class="flex items-center gap-2">
                            <label for="avatar_image" class="block w-40 px-2 py-2 bg-gray-100 text-gray-700 rounded cursor-pointer hover:bg-gray-200 transition text-center">
                                Pievienot attēlu
                            </label>
                        </div>
                        <span x-show="avatarFileName" x-text="avatarFileName" class="text-sm text-gray-500 ml-2"></span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Atbalstītie formāti: JPEG, PNG, JPG, GIF, WEBP. Maksimālais faila izmērs: 4MB</p>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="avatarOpen = false" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-custom-gray cursor-pointer">Atcelt</button>
                    <button type="submit" class="px-4 py-2 rounded bg-custom-darkyellow text-custom-black hover:bg-custom-lightyellow cursor-pointer">Saglabāt</button>
                </div>
            </form>
            @if ($errors->avatar->any())
                <ul class="rounded px-4 py-2 mt-4 bg-red-100">
                    @foreach ($errors->avatar->all() as $error)
                        <li class="my-2 text-red-500">{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="deleteModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center" style="background: rgba(0,0,0,0.4);">
        <div @click.away="deleteModalOpen = false" class="bg-white rounded-xl border border-gray-200 w-full max-w-md p-6 shadow-lg">
            <h2 class="text-xl font-bold mb-4">Dzēst sludinājumu</h2>
            <p class="text-gray-600 mb-6">Vai tiešām vēlaties dzēst šo sludinājumu? Šo darbību nevarēs atsaukt.</p>
            <div class="flex justify-end gap-2">
                <button 
                    @click="deleteModalOpen = false" 
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-custom-gray cursor-pointer"
                >
                    Atcelt
                </button>
                <form :action="'{{ url('/') }}/cars/' + carToDelete + '?redirect=profile'" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button 
                        type="submit" 
                        class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 cursor-pointer"
                    >
                        Dzēst
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Car Modal -->
    <div x-show="editCarOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center" style="background: rgba(0,0,0,0.4);">
        <div class="relative">
            <div class="absolute right-full mr-4 rounded-xl px-4 py-3 bg-red-50 border border-red-200 shadow-sm w-120" x-show="false">
                <ul class="space-y-1.5">
                    <!-- Error message -->
                </ul>
            </div>

            <div @click.away="closeEditCarModal" class="bg-white rounded-xl border border-gray-200 w-full max-w-4xl p-6 shadow-lg">
                <h2 class="text-xl font-bold mb-4">Rediģēt auto</h2>
                <form method="POST" :action="'{{ url('cars') }}/' + carToEdit + '?redirect=profile'" enctype="multipart/form-data" id="editCarForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="redirect" value="profile">
                    <template x-for="(image, index) in existingImages" :key="index">
                        <input type="hidden" :name="'existing_images[]'" :value="image">
                    </template>
                    
                    <div class="grid grid-cols-3 gap-4 mb-3">
                        <div class="mb-2">
                            <label class="block text-gray-700 mb-1" for="edit_make">Marka <span class="text-red-500">*</span></label>
                            <select name="make" id="edit_make" class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('editCar.make') border-red-500 @enderror">
                                <option value="">Izvēlieties marku</option>
                                <option value="Abarth">Abarth</option>
                                <option value="Alfa Romeo">Alfa Romeo</option>
                                <option value="Aston Martin">Aston Martin</option>
                                <option value="Audi">Audi</option>
                                <option value="BMW">BMW</option>
                                <option value="Chevrolet">Chevrolet</option>
                                <option value="Citroën">Citroën</option>
                                <option value="Dacia">Dacia</option>
                                <option value="Fiat">Fiat</option>
                                <option value="Ford">Ford</option>
                                <option value="Honda">Honda</option>
                                <option value="Hyundai">Hyundai</option>
                                <option value="Kia">Kia</option>
                                <option value="Lexus">Lexus</option>
                                <option value="Mazda">Mazda</option>
                                <option value="Mercedes-Benz">Mercedes-Benz</option>
                                <option value="Nissan">Nissan</option>
                                <option value="Opel">Opel</option>
                                <option value="Peugeot">Peugeot</option>
                                <option value="Renault">Renault</option>
                                <option value="Seat">Seat</option>
                                <option value="Skoda">Skoda</option>
                                <option value="Toyota">Toyota</option>
                                <option value="Volkswagen">Volkswagen</option>
                                <option value="Volvo">Volvo</option>
                            </select>
                            @error('editCar.make')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-700 mb-1" for="edit_model">Modelis <span class="text-red-500">*</span></label>
                            <select name="model" id="edit_model" class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('editCar.model') border-red-500 @enderror">
                                <option value="">Izvēlieties modeli</option>
                            </select>
                            @error('editCar.model')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-700 mb-1" for="edit_year">Gads <span class="text-red-500">*</span></label>
                            <input type="number" id="edit_year" name="year" min="1900" max="{{ date('Y') + 1 }}" 
                                class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('editCar.year') border-red-500 @enderror" placeholder="piem. 2018">
                            @error('editCar.year')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-700 mb-1" for="edit_body_type">Tips <span class="text-red-500">*</span></label>
                            <select id="edit_body_type" name="body_type" class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('editCar.body_type') border-red-500 @enderror">
                                <option value="">Izvēlieties</option>
                                <option value="sedan">Sedans</option>
                                <option value="SUV">Apvidus auto</option>
                                <option value="hatchback">Hečbeks</option>
                                <option value="coupe">Kupeja</option>
                                <option value="wagon">Universālis</option>
                                <option value="van">Furgons</option>
                                <option value="pickup">Pikaps</option>
                            </select>
                            @error('editCar.body_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-700 mb-1" for="edit_transmission">Pārnesumkārba <span class="text-red-500">*</span></label>
                            <select id="edit_transmission" name="transmission" class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('editCar.transmission') border-red-500 @enderror">
                                <option value="">Izvēlieties</option>
                                <option value="manual">Mehāniskā</option>
                                <option value="automatic">Automātiskā</option>
                            </select>
                            @error('editCar.transmission')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-700 mb-1" for="edit_fuel_type">Degviela <span class="text-red-500">*</span></label>
                            <select id="edit_fuel_type" name="fuel_type" class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('editCar.fuel_type') border-red-500 @enderror">
                                <option value="">Izvēlieties</option>
                                <option value="petrol">Benzīns</option>
                                <option value="diesel">Dīzelis</option>
                                <option value="electric">Elektriskais</option>
                                <option value="hybrid">Hibrīds</option>
                            </select>
                            @error('editCar.fuel_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-700 mb-1" for="edit_mileage">Nobraukums (km)</label>
                            <input type="number" id="edit_mileage" name="mileage" min="0" class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('editCar.mileage') border-red-500 @enderror" placeholder="piem. 50000">
                            @error('editCar.mileage')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-700 mb-1" for="edit_color">Krāsa</label>
                            <input type="text" id="edit_color" name="color" class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('editCar.color') border-red-500 @enderror" placeholder="piem. Melna">
                            @error('editCar.color')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-700 mb-1" for="edit_price">Cena (€) <span class="text-red-500">*</span></label>
                            <input type="number" id="edit_price" name="price" step="0.01" min="0" class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('editCar.price') border-red-500 @enderror" placeholder="piem. 15000">
                            @error('editCar.price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="block text-gray-700 mb-1" for="edit_location">Atrašanās vieta <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_location" name="location" class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('editCar.location') border-red-500 @enderror" placeholder="piem. Rīga">
                            @error('editCar.location')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-gray-700 mb-1" for="edit_description">Apraksts</label>
                        <textarea id="edit_description" name="description" class="block w-full px-3 py-1.5 border rounded focus:outline-none @error('editCar.description') border-red-500 @enderror" rows="2" placeholder="Aprakstiet savu auto..."></textarea>
                        @error('editCar.description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="edit_show_email" name="show_email" value="1" class="rounded border-gray-300 text-custom-darkyellow focus:ring-custom-darkyellow">
                            <label for="edit_show_email" class="text-gray-700">Rādīt manu e-pastu sludinājumā</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="block text-gray-700 mb-1" for="edit_images">Attēli (Varat pievienot līdz 6 attēliem.)</label>
                        <div class="relative">
                            <input type="file" id="edit_images" name="images[]" multiple="multiple" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" class="hidden @error('editCar.images') border-red-500 @enderror" @change="updateCount">
                            <div class="flex items-center gap-2">
                                <label for="edit_images" class="block w-40 px-2 py-2 bg-gray-100 text-gray-700 rounded cursor-pointer hover:bg-gray-200 transition text-center">
                                    Pievienot attēlus
                                </label>
                                <span x-show="errorMessage" x-text="errorMessage" class="text-sm text-red-500"></span>
                            </div>
                            @error('editCar.images')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            @error('editCar.images.*')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <span x-show="selectedFiles > 0" x-text="'Izvēlēti ' + selectedFiles + ' attēli'" class="text-sm text-gray-500 ml-2"></span>
                            
                            <div x-show="existingImages.length > 0 && previews.length === 0" class="grid grid-cols-6 gap-2 mt-2">
                                <template x-for="(image, index) in existingImages" :key="index">
                                    <div class="relative w-full">
                                        <img :src="window.appBaseUrl + 'storage/' + image" class="w-full h-15 object-cover rounded">
                                    </div>
                                </template>
                            </div>
                            
                            <div x-show="previews.length > 0" class="grid grid-cols-6 gap-2">
                                <template x-for="(preview, index) in previews" :key="index">
                                    <div class="relative w-full">
                                        <img :src="preview" class="w-full h-15 object-cover rounded">
                                    </div>
                                </template>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Atbalstītie formāti: JPEG, PNG, JPG, GIF, WEBP. Maksimālais faila izmērs: 4MB</p>
                    </div>
                    
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" @click="closeEditCarModal" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-custom-gray cursor-pointer">Atcelt</button>
                        <button type="button" onclick="document.getElementById('editCarForm').status.value='draft'; document.getElementById('editCarForm').submit();" class="px-4 py-2 rounded bg-yellow-200 text-yellow-800 hover:bg-yellow-300 cursor-pointer">Saglabāt kā melnrakstu</button>
                        <button type="submit" onclick="document.getElementById('editCarForm').status.value='published';" class="px-4 py-2 rounded bg-custom-darkyellow text-white hover:bg-custom-lightyellow cursor-pointer">Saglabāt</button>
                    </div>
                    <input type="hidden" name="status" value="published">
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Account Confirmation Modal -->
    <div x-show="deleteAccountOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center" style="background: rgba(0,0,0,0.4);">
        <div @click.away="deleteAccountOpen = false" class="bg-white rounded-xl border border-gray-200 w-full max-w-md p-6 shadow-lg">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-red-800">Dzēst kontu</h2>
            </div>
            <p class="text-gray-600 mb-6">Vai tiešām vēlaties dzēst savu kontu? Šī darbība ir neatgriezeniska un visi jūsu dati, sludinājumi un faili tiks neatgriezeniski dzēsti.</p>
            <div class="flex justify-end gap-2">
                <button 
                    @click="deleteAccountOpen = false" 
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-custom-gray cursor-pointer"
                >
                    Atcelt
                </button>
                <form action="{{ route('profile.delete') }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button 
                        type="submit" 
                        class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 cursor-pointer"
                    >
                        Dzēst kontu
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const makeSelect = document.getElementById('make');
    const modelSelect = document.getElementById('model');
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

    makeSelect.addEventListener('change', function() {
        const selectedMake = this.value;
        modelSelect.innerHTML = '<option value="">Izvēlieties modeli</option>';
        
        if (selectedMake && models[selectedMake]) {
            models[selectedMake].forEach(model => {
                const option = document.createElement('option');
                option.value = model;
                option.textContent = model;
                if (model === '{{ old('model') }}') {
                    option.selected = true;
                }
                modelSelect.appendChild(option);
            });
        }
    });

    if (makeSelect.value) {
        makeSelect.dispatchEvent(new Event('change'));
    }

    window.appBaseUrl = "{{ asset('') }}";

    function profilePageData(open, addCarOpen, avatarOpen) {
        return {
            open: open,
            addCarOpen: addCarOpen,
            avatarOpen: avatarOpen,
            deleteModalOpen: false,
            deleteAccountOpen: false,
            carToDelete: null,
            editCarOpen: false,
            carToEdit: null,
            selectedFiles: 0,
            previews: [],
            existingImages: [],
            errorMessage: '',
            avatarFileName: '',
            closeAddCarModal() {
                if (!document.querySelector('[x-show="addCarOpen"] .text-red-600')) {
                    this.addCarOpen = false;
                    window.location.href = window.location.pathname;
                }
            },
            closeEditCarModal() {
                if (!document.querySelector('[x-show="editCarOpen"] .text-red-600')) {
                    this.editCarOpen = false;
                    window.location.href = window.location.pathname;
                }
            },
            updateAvatarFileName(e) {
                const file = e.target.files[0];
                if (file) {
                    this.avatarFileName = file.name;
                } else {
                    this.avatarFileName = '';
                }
            },
            openEditCar(car) {
                const modelSelect = document.getElementById("edit_model");
                modelSelect.innerHTML = '<option value="">Izvēlieties modeli</option>';
                if (car.make && models[car.make]) {
                    models[car.make].forEach(model => {
                        const option = document.createElement("option");
                        option.value = model;
                        option.textContent = model;
                        if (model === car.model) option.selected = true;
                        modelSelect.appendChild(option);
                    });
                }
                document.getElementById("edit_make").value = car.make;
                document.getElementById("edit_year").value = car.year;
                document.getElementById("edit_body_type").value = car.body_type;
                document.getElementById("edit_transmission").value = car.transmission;
                document.getElementById("edit_fuel_type").value = car.fuel_type;
                document.getElementById("edit_mileage").value = car.mileage;
                document.getElementById("edit_color").value = car.color;
                document.getElementById("edit_price").value = car.price;
                document.getElementById("edit_location").value = car.location;
                document.getElementById("edit_description").value = car.description;
                document.getElementById("edit_show_email").checked = car.show_email;
                this.carToEdit = car.id;
                this.editCarOpen = true;
                this.selectedFiles = 0;
                this.previews = [];
                this.errorMessage = '';
                this.existingImages = car.images.map(img => img.image_path);
            },
            updateCount(e) {
                const files = e.target.files;
                if (files.length > 6) {
                    this.errorMessage = 'Varat pievienot maksimāli 6 attēlus';
                    e.target.value = '';
                    this.selectedFiles = 0;
                    this.previews = [];
                    this.existingImages = [];
                    return;
                }
                this.errorMessage = '';
                this.selectedFiles = files.length;
                this.previews = [];
                this.existingImages = [];
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.previews.push(e.target.result);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            },
            openAddCar() {
                this.addCarOpen = true;
                this.selectedFiles = 0;
                this.previews = [];
                this.existingImages = [];
                this.errorMessage = '';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const addCarForm = document.getElementById('addCarForm');
        const editCarForm = document.getElementById('editCarForm');
        
        if (addCarForm) {
            addCarForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const requiredFields = [
                    { name: 'make', label: 'Marka' },
                    { name: 'model', label: 'Modelis' },
                    { name: 'year', label: 'Gads' },
                    { name: 'body_type', label: 'Tips' },
                    { name: 'transmission', label: 'Pārnesumkārba' },
                    { name: 'fuel_type', label: 'Degviela' },
                    { name: 'price', label: 'Cena' },
                    { name: 'location', label: 'Atrašanās vieta' }
                ];

                let hasErrors = false;
                const errorMessages = [];

                requiredFields.forEach(field => {
                    const input = addCarForm.querySelector(`[name="${field.name}"]`);
                    if (!input.value.trim()) {
                        hasErrors = true;
                        input.classList.add('border-red-500');
                        errorMessages.push(`${field.label} ir obligāts lauks`);
                    } else {
                        input.classList.remove('border-red-500');
                    }
                });

                const fileInput = addCarForm.querySelector('#images');
                const newImages = fileInput.files.length;

                if (newImages === 0) {
                    hasErrors = true;
                    fileInput.classList.add('border-red-500');
                    errorMessages.push('Lūdzu, pievienojiet vismaz vienu attēlu');
                } else {
                    fileInput.classList.remove('border-red-500');
                }

                if (hasErrors) {
                    let errorContainer = document.querySelector('[x-show="addCarOpen"] .bg-red-50');
                    if (!errorContainer) {
                        errorContainer = document.createElement('div');
                        errorContainer.className = 'absolute right-full mr-4 rounded-xl px-4 py-3 bg-red-50 border border-red-200 shadow-sm w-120';
                        const ul = document.createElement('ul');
                        ul.className = 'space-y-1.5';
                        errorContainer.appendChild(ul);
                        addCarForm.parentElement.insertBefore(errorContainer, addCarForm);
                    }

                    const ul = errorContainer.querySelector('ul');
                    ul.innerHTML = errorMessages.map(msg => `<li class="text-red-600">${msg}</li>`).join('');
                    errorContainer.style.display = 'block';
                    return;
                }

                const formData = new FormData(addCarForm);
                
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }

                fetch(addCarForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else if (response.headers.get('content-type')?.includes('application/json')) {
                        return response.json();
                    } else {
                        // fallback: show a generic error or reload
                        window.location.reload();
                    }
                })
                .then(data => {
                    if (data && data.success) {
                        const successMessage = data.message || 'Auto veiksmīgi atjaunināts!';
                        window.location.href = '{{ route("profile") }}?success_message=' + encodeURIComponent(successMessage);
                    } else if (data && data.errors) {
                        const errorContainer = document.querySelector('[x-show="addCarOpen"] .bg-red-50');
                        const ul = errorContainer.querySelector('ul');
                        ul.innerHTML = Object.values(data.errors).flat().map(error => 
                            `<li class="text-red-600">${error}</li>`
                        ).join('');
                        errorContainer.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        }

        if (editCarForm) {
            editCarForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const requiredFields = [
                    { name: 'make', label: 'Marka' },
                    { name: 'model', label: 'Modelis' },
                    { name: 'year', label: 'Gads' },
                    { name: 'body_type', label: 'Tips' },
                    { name: 'transmission', label: 'Pārnesumkārba' },
                    { name: 'fuel_type', label: 'Degviela' },
                    { name: 'price', label: 'Cena' },
                    { name: 'location', label: 'Atrašanās vieta' }
                ];

                let hasErrors = false;
                const errorMessages = [];

                requiredFields.forEach(field => {
                    const input = editCarForm.querySelector(`[name="${field.name}"]`);
                    if (!input.value.trim()) {
                        hasErrors = true;
                        input.classList.add('border-red-500');
                        errorMessages.push(`${field.label} ir obligāts lauks`);
                    } else {
                        input.classList.remove('border-red-500');
                    }
                });

                if (hasErrors) {
                    let errorContainer = document.querySelector('[x-show="editCarOpen"] .bg-red-50');
                    if (!errorContainer) {
                        errorContainer = document.createElement('div');
                        errorContainer.className = 'absolute right-full mr-4 rounded-xl px-4 py-3 bg-red-50 border border-red-200 shadow-sm w-120';
                        const ul = document.createElement('ul');
                        ul.className = 'space-y-1.5';
                        errorContainer.appendChild(ul);
                        editCarForm.parentElement.insertBefore(errorContainer, editCarForm);
                    }

                    const ul = errorContainer.querySelector('ul');
                    ul.innerHTML = errorMessages.map(msg => `<li class="text-red-600">${msg}</li>`).join('');
                    errorContainer.style.display = 'block';
                    return;
                }

                const formData = new FormData(editCarForm);
                
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }

                fetch(editCarForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else {
                        return response.json();
                    }
                })
                .then(data => {
                    if (data && data.success) {
                        const successMessage = data.message || 'Auto veiksmīgi atjaunināts!';
                        window.location.href = '{{ route("profile") }}?success_message=' + encodeURIComponent(successMessage);
                    } else if (data && data.errors) {
                        const errorContainer = document.querySelector('[x-show="editCarOpen"] .bg-red-50');
                        const ul = errorContainer.querySelector('ul');
                        ul.innerHTML = Object.values(data.errors).flat().map(error => 
                            `<li class="text-red-600">${error}</li>`
                        ).join('');
                        errorContainer.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        }
    });
</script>
@endsection