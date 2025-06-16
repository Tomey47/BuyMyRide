@extends('admin.layouts.admin')

@section('content')
<div x-data="{ modalOpen: false, selectedCar: null, deleteModalOpen: false, carToDelete: null, approveModalOpen: false, carToApprove: null, ignoreModalOpen: false, carToIgnore: null }">
    <div class="">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden border border-gray-200 sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Tabs -->
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <a href="{{ route('admin.listings.index') }}" 
                               class="{{ request()->routeIs('admin.listings.index') ? 'border-custom-darkyellow text-custom-darkyellow' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Sludinājumi
                            </a>
                            <a href="{{ route('admin.listings.pending') }}" 
                               class="{{ request()->routeIs('admin.listings.pending') ? 'border-custom-darkyellow text-custom-darkyellow' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Gaida apstiprināšanu
                            </a>
                            <a href="{{ route('admin.listings.reported') }}" 
                               class="{{ request()->routeIs('admin.listings.reported') ? 'border-custom-darkyellow text-custom-darkyellow' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Nosūdzētie
                            </a>
                        </nav>
                    </div>

                    <!-- Table -->
                    <div class="mt-8 flex flex-col">
                        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                                <div class="overflow-hidden border border-gray-200 md:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Auto</th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Lietotājs</th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Cena</th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Statuss</th>
                                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                    <span class="sr-only">Darbības</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">
                                            @foreach($cars as $car)
                                            <tr class="hover:bg-gray-50 cursor-pointer" @click="modalOpen = true; selectedCar = {{ json_encode($car->load(['user', 'images'])) }}">
                                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                                    <div class="flex items-center">
                                                        <div class="h-10 w-10 flex-shrink-0">
                                                            <img class="h-10 w-10 rounded-full object-cover border border-gray-200" :src="'{{ asset('storage') }}/' + '{{ $car->images->first()->image_path ?? '' }}'" alt="">
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="font-medium text-gray-900">{{ $car->make }} {{ $car->model }}</div>
                                                            <div class="text-gray-500">{{ $car->year }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    <div class="text-gray-900">{{ $car->user->name }}</div>
                                                    <div class="text-gray-500">{{ $car->user->email }}</div>
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ currency_symbol() }}{{ number_format(convert_currency($car->price), 2) }}</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    @if($car->is_approved)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            Apstiprināts
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            Gaida Apstiprināšanu
                                                        </span>
                                                    @endif
                                                    @if($car->is_reported)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                                            Nosūdzēts
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                    <div class="flex space-x-3">
                                                        @if(request()->routeIs('admin.listings.reported'))
                                                            <button @click.stop="deleteModalOpen = true; carToDelete = {{ $car->id }}" class="text-red-600 hover:text-red-900 cursor-pointer">Dzēst</button>
                                                            <button @click.stop="ignoreModalOpen = true; carToIgnore = {{ $car->id }}" class="text-gray-600 hover:text-gray-900 cursor-pointer">Ignorēt</button>
                                                        @elseif(!$car->is_approved && request()->routeIs('admin.listings.pending'))
                                                            <button @click.stop="approveModalOpen = true; carToApprove = {{ $car->id }}" class="text-green-600 hover:text-green-900 cursor-pointer">Apstiprināt</button>
                                                            <button @click.stop="deleteModalOpen = true; carToDelete = {{ $car->id }}" class="text-red-600 hover:text-red-900 cursor-pointer">Dzēst</button>
                                                        @else
                                                            <button @click.stop="deleteModalOpen = true; carToDelete = {{ $car->id }}" class="text-red-600 hover:text-red-900 cursor-pointer">Dzēst</button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        {{ $cars->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div x-show="modalOpen" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center" 
         style="background: rgba(0,0,0,0.4);">
        <div @click.away="modalOpen = false; selectedCar = null; selectedImage = null; fullscreenImage = false" class="bg-white rounded-xl border border-gray-200 w-full max-w-4xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Sludinājuma Detalizēta Informācija</h3>
                <button @click="modalOpen = false; selectedCar = null; selectedImage = null; fullscreenImage = false" class="text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Aizvērt</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <template x-if="selectedCar">
                <div class="mt-4">
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 mb-3">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Lietotāja Informācija</h4>
                        <div class="w-full">
                            <div class="flex gap-6 font-semibold text-xs text-gray-500 uppercase tracking-wide mb-1">
                                <div class="mr-6">Vārds</div>
                                <div class="">E-pasts</div>
                            </div>
                            <div class="flex items-center text-base">
                                <div class="font-medium text-custom-black inline-block mr-10" x-text="selectedCar.user.username"></div>
                                <div class="font-medium text-custom-black inline-block" x-text="selectedCar.user.email"></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 mb-3">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Auto Informācija</h4>
                        <div class="flex flex-wrap gap-6 mb-4">
                            <div>
                                <dt class="text-xs text-gray-500 uppercase tracking-wide">Marka</dt>
                                <dd class="font-medium text-custom-black mt-1" x-text="selectedCar.make"></dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500 uppercase tracking-wide">Modelis</dt>
                                <dd class="font-medium text-custom-black mt-1" x-text="selectedCar.model"></dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500 uppercase tracking-wide">Gads</dt>
                                <dd class="font-medium text-custom-black mt-1" x-text="selectedCar.year"></dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500 uppercase tracking-wide">Cena</dt>
                                <dd class="font-medium text-custom-black mt-1" x-text="selectedCar.price"></dd>
                            </div>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 uppercase tracking-wide">Apraksts</dt>
                            <textarea readonly rows="4" class="block w-full mt-1 rounded border border-gray-300 bg-gray-100 text-custom-black resize-none" x-text="selectedCar.description"></textarea>
                        </div>
                    </div>

                    <div x-data="{ selectedImage: null, fullscreenImage: false }" class="mt-3">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Attēli</h4>
                        <template x-if="selectedCar.images && selectedCar.images.length">
                            <div>
                                <div class="flex justify-center mb-4">
                                    <img :src="selectedImage ? '{{ asset('storage') }}/' + selectedImage : '{{ asset('storage') }}/' + selectedCar.images[0].image_path"
                                         :alt="selectedCar.make + ' ' + selectedCar.model"
                                         class="h-72 w-full max-w-xl object-cover rounded border border-gray-200 cursor-zoom-in transition-all duration-200"
                                         @click="fullscreenImage = true"
                                    >
                                </div>
                                <div class="flex gap-2 pb-2 overflow-x-auto">
                                    <template x-for="(image, idx) in selectedCar.images" :key="image.id">
                                        <img :src="'{{ asset('storage') }}/' + image.image_path"
                                             class="h-14 w-20 object-cover rounded border border-gray-200 cursor-pointer transition-all duration-150 hover:scale-105"
                                             :class="(selectedImage ? '{{ asset('storage') }}/' + selectedImage : '{{ asset('storage') }}/' + selectedCar.images[0].image_path) === ('{{ asset('storage') }}/' + image.image_path) ? 'ring-2 ring-custom-darkyellow' : ''"
                                             @click="selectedImage = image.image_path"
                                             :alt="selectedCar.make + ' ' + selectedCar.model"
                                        >
                                    </template>
                                </div>
                                <div x-show="fullscreenImage" x-transition class="fixed inset-0 z-60 flex items-center justify-center bg-black bg-opacity-80" @click.away="fullscreenImage = false" @keydown.escape.window="fullscreenImage = false">
                                    <img :src="selectedImage ? '{{ asset('storage') }}/' + selectedImage : '{{ asset('storage') }}/' + selectedCar.images[0].image_path" class="max-h-[90vh] max-w-[90vw] rounded border border-gray-200" :alt="selectedCar.make + ' ' + selectedCar.model">
                                    <button @click="fullscreenImage = false" class="absolute top-4 right-4 text-white text-3xl font-bold">&times;</button>
                                </div>
                            </div>
                        </template>
                        <template x-if="!selectedCar.images || !selectedCar.images.length">
                            <div class="h-60 w-full bg-gray-200 flex items-center justify-center rounded-lg text-gray-400 border border-gray-200">Nav attēla</div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="deleteModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center" 
         style="background: rgba(0,0,0,0.4);">
        <div @click.away="deleteModalOpen = false" class="bg-white rounded-xl border border-gray-200 w-full max-w-md p-6">
            <h2 class="text-xl font-bold mb-4">Dzēst sludinājumu</h2>
            <p class="text-gray-600 mb-6">Vai tiešām vēlaties dzēst šo sludinājumu? Šo darbību nevarēs atsaukt.</p>
            <div class="flex justify-end gap-2">
                <button
                    @click="deleteModalOpen = false"
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-custom-gray cursor-pointer"
                >
                    Atcelt
                </button>
                <form :action="'{{ url('admin/listings') }}/' + carToDelete" method="POST" class="inline">
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

    <!-- Approve Modal -->
    <div x-show="approveModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center" 
         style="background: rgba(0,0,0,0.4);">
        <div @click.away="approveModalOpen = false" class="bg-white rounded-xl border border-gray-200 w-full max-w-md p-6">
            <h2 class="text-xl font-bold mb-4">Apstiprināt sludinājumu</h2>
            <p class="text-gray-600 mb-6">Vai tiešām vēlaties apstiprināt šo sludinājumu?</p>
            <div class="flex justify-end gap-2">
                <button
                    @click="approveModalOpen = false"
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-custom-gray cursor-pointer"
                >
                    Atcelt
                </button>
                <form :action="'{{ url('admin/listings') }}/' + carToApprove + '/approve'" method="POST" class="inline">
                    @csrf
                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 cursor-pointer"
                    >
                        Apstiprināt
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Ignore Modal -->
    <div x-show="ignoreModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center" 
         style="background: rgba(0,0,0,0.4);">
        <div @click.away="ignoreModalOpen = false" class="bg-white rounded-xl border border-gray-200 w-full max-w-md p-6">
            <h2 class="text-xl font-bold mb-4">Ignorēt sūdzību</h2>
            <p class="text-gray-600 mb-6">Vai tiešām vēlaties ignorēt šo sūdzību? Sludinājums paliks aktīvs un tiks noņemta atzīme "Nosūdzēts".</p>
            <div class="flex justify-end gap-2">
                <button
                    @click="ignoreModalOpen = false"
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-custom-gray cursor-pointer"
                >
                    Atcelt
                </button>
                <form :action="'{{ url('admin/listings') }}/' + carToIgnore + '/ignore-report'" method="POST" class="inline">
                    @csrf
                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg bg-gray-600 text-white hover:bg-gray-700 cursor-pointer"
                    >
                        Ignorēt
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 