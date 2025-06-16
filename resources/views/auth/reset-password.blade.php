@extends('layouts.app')

@section('title', 'Atiestatīt paroli')

@section('content')
<div class="flex items-center justify-center py-34">
    <form action="{{ route('password.update') }}" method="POST" class="bg-white rounded border border-gray-200 p-8 max-w-md w-full">
        @csrf

        <h2 class="text-2xl font-bold mb-6 text-center text-custom-black">Atiestatīt paroli</h2>

        <p class="text-gray-600 mb-6 text-center">
            Ievadiet jauno paroli savam kontam.
        </p>

        <!-- Hidden fields -->
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <label for="email" class="block mb-2 font-medium text-custom-gray">E-pasta adrese</label>
        <input 
            type="email"
            name="email"
            value="{{ $email }}"
            class="block w-full mb-4 px-4 py-2 border border-gray-400 rounded focus:outline-none text-custom-gray focus:border-custom-black bg-gray-100"
            readonly
        >

        <label for="password" class="block mb-2 font-medium text-custom-gray">Jaunā parole</label>
        <input 
            type="password"
            name="password"
            class="block w-full mb-4 px-4 py-2 border border-gray-400 rounded focus:outline-none text-custom-gray focus:border-custom-black"
            required
        >

        <label for="password_confirmation" class="block mb-2 font-medium text-custom-gray">Apstiprināt jauno paroli</label>
        <input 
            type="password"
            name="password_confirmation"
            class="block w-full mb-4 px-4 py-2 border border-gray-400 rounded focus:outline-none text-custom-gray focus:border-custom-black"
            required
        >

        <button type="submit" class="w-full bg-custom-lightyellow text-custom-black py-2 rounded hover:bg-custom-darkyellow transition font-semibold mt-2 cursor-pointer">
            Atiestatīt paroli
        </button>
        
        <!-- Validation ERRORS -->
        @if ($errors->any())
            <ul class="rounded px-4 py-2 mt-4 bg-red-100">
                @foreach ($errors->all() as $error)
                    <li class="my-2 text-red-500">{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <div class="text-center mt-4">
            <span class="text-custom-gray">Atcerējāties paroli?</span>
            <a href="{{ route('show.login') }}" class="text-custom-darkyellow font-semibold hover:underline">Ienākt sistēmā</a>
        </div>
        
    </form>
</div>
@endsection 