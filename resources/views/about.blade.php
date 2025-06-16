@extends('layouts.app')

@section('title', 'Par BuyMyRide')

@section('content')
<div class="container mx-auto px-4 py-8 md:py-12">
    <!-- Main -->
    <div class="text-center mb-8 md:mb-16">
        <h1 class="text-3xl md:text-4xl font-bold text-custom-black mb-4">BuyMyRide</h1>
        <p class="text-lg md:text-xl text-custom-gray max-w-3xl mx-auto">
            Jūsu uzticamā platforma auto pirkšanai un pārdošanai Latvijā. Mēs savienojam auto entuziastus ar viņu ideālo auto.
        </p>
    </div>

    <div class="border-t border-gray-200 my-8 md:my-16"></div>
    
    <!-- Mission -->
    <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-center mb-8 md:mb-16">
        <div class="order-2 md:order-1">
            <h2 class="text-2xl md:text-3xl font-semibold text-custom-black mb-4">Mūsu Misija</h2>
            <p class="text-base md:text-lg text-custom-gray mb-4 md:mb-6">
                BuyMyRide mērķis ir padarīt auto pirkšanas un pārdošanas procesu pēc iespējas vienkāršāku un caurspīdīgāku. 
                Mēs uzskatām, ka ikvienam ir jābūt uzticamai un patīkamai auto pirkšanas pieredzei.
            </p>
            <p class="text-base md:text-lg text-custom-gray">
                Mūsu platforma apvieno auto entuziastus, pārdevējus un pircējus uzticamā vidē, kur kvalitāte sastopas ar ērtību.
            </p>
        </div>
        <div class="flex justify-center order-1 md:order-2 mb-6 md:mb-0">
            <img src="{{ asset('images/parts.png') }}" alt="BuyMyRide Misija" class="w-full max-w-sm md:max-w-md">
        </div>
    </div>

    <div class="border-t border-gray-200 my-8 md:my-16"></div>

    <!-- Features -->
    <div class="mb-8 md:mb-16">
        <h2 class="text-2xl md:text-3xl font-semibold text-custom-black text-center mb-8 md:mb-12">Kāpēc izvēlēties BuyMyRide?</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-8">
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <div class="text-custom-darkyellow mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 md:h-12 md:w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-lg md:text-xl font-semibold mb-2">Uzticama Platforma</h3>
                <p class="text-sm md:text-base text-custom-gray">Verificēti pārdevēji un detalizēta auto informācija nodrošina drošu un uzticamu auto pirkšanas pieredzi.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <div class="text-custom-darkyellow mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 md:h-12 md:w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-lg md:text-xl font-semibold mb-2">Vienkārša Meklēšana</h3>
                <p class="text-sm md:text-base text-custom-gray">Attīstītas filtrēšanas iespējas palīdz atrast tieši to, ko meklējat - no markas un modeļa līdz cenas diapazonam.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow sm:col-span-2 md:col-span-1">
                <div class="text-custom-darkyellow mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 md:h-12 md:w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                </div>
                <h3 class="text-lg md:text-xl font-semibold mb-2">24/7 Atbalsts</h3>
                <p class="text-sm md:text-base text-custom-gray">Mūsu atvēlētā atbalsta komanda vienmēr ir gatava palīdzēt ar jebkādiem jautājumiem.</p>
            </div>
        </div>
    </div>

    <div class="border-t border-gray-200 my-8 md:my-16"></div>

    <!-- Contact -->
    <div class="text-center">
        <h2 class="text-2xl md:text-3xl font-semibold text-custom-black mb-4">Sazinieties ar Mums</h2>
        <p class="text-base md:text-lg text-custom-gray mb-4">
            Ir jautājumi? Mēs esam šeit, lai palīdzētu!
        </p>
        <a href="mailto:buymyrides@gmail.com" class="text-custom-darkyellow hover:text-custom-lightyellow transition-colors text-lg md:text-xl">
            buymyrides@gmail.com
        </a>
    </div>
</div>
@endsection