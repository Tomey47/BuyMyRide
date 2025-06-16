@extends('layouts.app')

@section('title', 'Apstiprināt e-pastu')

@section('content')
<div class="flex items-center justify-center py-34">
    <div class="bg-white rounded border border-gray-200 p-8 max-w-md w-full">
        <div class="text-center">
            <h2 class="text-2xl font-bold mb-6 text-custom-black">Apstipriniet savu e-pastu</h2>
            
            <div class="mb-6">
                <svg class="mx-auto h-12 w-12 text-custom-darkyellow mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                
                <p class="text-gray-600 mb-4">
                    Pirms turpināt, lūdzu, pārbaudiet savu e-pastu apstiprinājuma saitei.
                </p>
                
                <p class="text-sm text-gray-500 mb-6">
                    Ja nesaņēmāt e-pastu, mēs varam nosūtīt citu.
                </p>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                @csrf
                <button type="submit" class="w-full bg-custom-darkyellow text-white py-2 px-4 rounded hover:bg-custom-lightyellow transition font-semibold">
                    Nosūtīt apstiprinājuma e-pastu vēlreiz
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mb-4">
                @csrf
                <button type="submit" class="w-full bg-gray-200 text-gray-700 py-2 px-4 rounded hover:bg-gray-300 transition font-semibold">
                    Izrakstīties
                </button>
            </form>

            <div class="text-sm text-gray-500">
                <p>Jautājumi? Sazinieties ar mūsu atbalsta komandu.</p>
            </div>
        </div>
    </div>
</div>
@endsection 