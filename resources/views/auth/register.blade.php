@extends('layouts.app')

@section('title', 'Reģistrācija')

@section('content')
<div class="container mx-auto py-10">
    <form action="{{ route('register') }}" method="POST" class="bg-white rounded shadow p-8 max-w-md mx-auto">
        @csrf

        <h2 class="text-2xl font-bold mb-6">Reģistrācija</h2>

        <label for="username" class="block mb-2 font-medium text-custom-gray">Lietotājvārds</label>
        <input 
            type="text"
            name="username"
            value="{{ old('username') }}"
            class="block w-full mb-4 px-4 py-2 border border-gray-400 rounded focus:outline-none text-custom-gray focus:border-custom-black"
        >

        <label for="email" class="block mb-2 font-medium text-custom-gray">E-pasta adrese</label>
        <input 
            type="email"
            name="email"
            value="{{ old('email') }}"
            class="block w-full mb-4 px-4 py-2 border border-gray-400 rounded focus:outline-none text-custom-gray focus:border-custom-black"
        >

        <label for="phone_number" class="block mb-2 font-medium text-custom-gray">Tālruņa numurs</label>
        <div class="flex gap-2 mb-4 items-center">
            <div class="relative w-40">
                <select 
                    id="country_code" 
                    name="country_code"
                    class="w-full block px-2 py-2 border border-gray-400 rounded focus:outline-none text-custom-gray focus:border-custom-black appearance-none bg-gray-100 cursor-pointer"
                    size="1"
                >
                    @foreach($countryCodes as $country)
                        <option 
                            value="{{ $country['code'] }}" 
                            {{ old('country_code', '+371') == $country['code'] ? 'selected' : '' }}
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
                value="{{ old('phone_number') }}"
                class="block w-full px-4 py-2 border border-gray-400 rounded focus:outline-none text-custom-gray focus:border-custom-black"
                placeholder="Tālruņa numurs"
            >
        </div>

        <label for="password" class="block mb-2 font-medium text-custom-gray">Parole</label>
        <input 
            type="password"
            name="password"
            class="block w-full mb-4 px-4 py-2 border border-gray-400 rounded focus:outline-none text-custom-gray focus:border-custom-black"
        >

        <label for="password_confirmation" class="block mb-2 font-medium text-custom-gray">Apstiprināt paroli</label>
        <input 
            type="password"
            name="password_confirmation"
            class="block w-full mb-4 px-4 py-2 border border-gray-400 rounded focus:outline-none text-custom-gray focus:border-custom-black"
        >
        <!-- Captcha -->
        <div class="mb-4">
            {!! NoCaptcha::display() !!}
        </div>

        <button type="submit" class="w-full bg-custom-lightyellow text-custom-gray py-2 rounded hover:bg-custom-darkyellow transition font-semibold mt-2 cursor-pointer">Reģistrēties</button>

        <!-- Validation ERRORS -->
        @if ($errors->any())
            <ul class="rounded px-4 py-2 mt-4 bg-red-100">
                @foreach ($errors->all() as $error)
                    <li class="my-2 text-red-500">{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <div class="text-center mt-4">
            <span class="text-custom-gray">Jau ir konts?</span>
            <a href="{{ route('show.login') }}" class="text-custom-darkyellow font-semibold hover:underline">Ienākt sistēmā</a>
        </div>
    </form>
</div>
{!! NoCaptcha::renderJs() !!}
@endsection