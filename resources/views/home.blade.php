@extends('layouts.app')
@section('title', 'Sākums')

@section('content')
<div class="w-full bg-custom-lightyellow py-3 mb-8 rounded">
    <div class="container mx-auto px-4">
        <!-- Mobile Brand Navigation -->
        <div class="md:hidden">
            <div class="relative" x-data="{ open: false }">
                <button 
                    @click="open = !open"
                    class="w-full flex items-center justify-between bg-white px-4 py-2 rounded-lg shadow-sm"
                >
                    <span class="font-semibold text-custom-gray">Auto markas</span>
                    <svg class="w-5 h-5 text-custom-gray" :class="{ 'transform rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div 
                    x-show="open"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="absolute z-10 w-full mt-2 bg-white rounded-lg shadow-lg py-2"
                >
                    <a href="{{ url('/cars?make=Audi') }}" class="block px-4 py-2 text-custom-gray hover:bg-custom-lightyellow">Audi</a>
                    <a href="{{ url('/cars?make=BMW') }}" class="block px-4 py-2 text-custom-gray hover:bg-custom-lightyellow">BMW</a>
                    <a href="{{ url('/cars?make=Mercedes-Benz') }}" class="block px-4 py-2 text-custom-gray hover:bg-custom-lightyellow">Mercedes-Benz</a>
                    <a href="{{ url('/cars?make=Lexus') }}" class="block px-4 py-2 text-custom-gray hover:bg-custom-lightyellow">Lexus</a>
                    <a href="{{ url('/cars?make=Honda') }}" class="block px-4 py-2 text-custom-gray hover:bg-custom-lightyellow">Honda</a>
                    <a href="{{ url('/cars?make=Mazda') }}" class="block px-4 py-2 text-custom-gray hover:bg-custom-lightyellow">Mazda</a>
                    <a href="{{ url('/cars?make=Nissan') }}" class="block px-4 py-2 text-custom-gray hover:bg-custom-lightyellow">Nissan</a>
                    <a href="{{ url('/cars?make=Skoda') }}" class="block px-4 py-2 text-custom-gray hover:bg-custom-lightyellow">Skoda</a>
                    <a href="{{ url('/cars?make=Toyota') }}" class="block px-4 py-2 text-custom-gray hover:bg-custom-lightyellow">Toyota</a>
                    <a href="{{ url('/cars?make=Volkswagen') }}" class="block px-4 py-2 text-custom-gray hover:bg-custom-lightyellow">Volkswagen</a>
                    <a href="{{ url('/cars?make=Volvo') }}" class="block px-4 py-2 text-custom-gray hover:bg-custom-lightyellow">Volvo</a>
                </div>
            </div>
        </div>

        <!-- Desktop Brand Navigation -->
        <nav class="hidden md:flex flex-wrap gap-6 justify-center">
            <a href="{{ url('/cars?make=Audi') }}" class="text-custom-gray font-semibold hover:text-custom-black transition-colors">Audi</a>
            <a href="{{ url('/cars?make=BMW') }}" class="text-custom-gray font-semibold hover:text-custom-black transition-colors">BMW</a>
            <a href="{{ url('/cars?make=Mercedes-Benz') }}" class="text-custom-gray font-semibold hover:text-custom-black transition-colors">Mercedes-Benz</a>
            <a href="{{ url('/cars?make=Lexus') }}" class="text-custom-gray font-semibold hover:text-custom-black transition-colors">Lexus</a>
            <a href="{{ url('/cars?make=Honda') }}" class="text-custom-gray font-semibold hover:text-custom-black transition-colors">Honda</a>
            <a href="{{ url('/cars?make=Mazda') }}" class="text-custom-gray font-semibold hover:text-custom-black transition-colors">Mazda</a>
            <a href="{{ url('/cars?make=Nissan') }}" class="text-custom-gray font-semibold hover:text-custom-black transition-colors">Nissan</a>
            <a href="{{ url('/cars?make=Skoda') }}" class="text-custom-gray font-semibold hover:text-custom-black transition-colors">Skoda</a>
            <a href="{{ url('/cars?make=Toyota') }}" class="text-custom-gray font-semibold hover:text-custom-black transition-colors">Toyota</a>
            <a href="{{ url('/cars?make=Volkswagen') }}" class="text-custom-gray font-semibold hover:text-custom-black transition-colors">Volkswagen</a>
            <a href="{{ url('/cars?make=Volvo') }}" class="text-custom-gray font-semibold hover:text-custom-black transition-colors">Volvo</a>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 py-8 md:py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        <div class="text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-bold text-custom-black mb-4">Laipni lūdzam BuyMyRide</h1>
            <p class="text-base md:text-lg text-custom-gray mb-6">
                BuyMyRide ir Jūsu uzticamā platforma auto pirkšanai un pārdošanai. 
                Pievienojiet savu auto pārdošanai un sasniedziet ieinteresētus pircējus vai pārlūkojiet plašu auto klāstu, lai atrastu sev piemērotāko auto. 
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                <a href="{{ url('/cars') }}" class="inline-block bg-custom-darkyellow text-custom-gray px-6 py-3 rounded-lg hover:bg-custom-lightyellow transition font-semibold text-lg shadow-sm hover:shadow-md">Sākt pārlūkot</a>
            </div>
        </div>
        <div class="flex justify-center order-first md:order-last">
            <img src="{{ asset('images/parts.png') }}" alt="Pārlūkot" class="w-full max-w-sm md:max-w-md">
        </div>
    </div>
</div>
@endsection