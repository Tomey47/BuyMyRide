@extends('layouts.app')

@section('title', 'Ienākt sistēmā')

@section('content')
<div class="px-4 py-8 sm:py-20 flex items-center justify-center">
    <form action="{{ route('login') }}" method="POST" class="bg-white rounded border border-gray-200 p-4 sm:p-8 max-w-md w-full shadow">
        @csrf

        <h2 class="text-xl sm:text-2xl font-bold mb-6 text-center text-custom-black">Ienākt sistēmā</h2>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (request('account_deleted') === 'true')
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                Jūsu konts ir veiksmīgi dzēsts.
            </div>
        @endif

        <label for="email" class="block mb-2 font-medium text-custom-gray">E-pasta adrese</label>
        <input 
            type="email"
            name="email"
            value="{{ old('email') }}"
            class="block w-full mb-4 px-4 py-2 border border-gray-400 rounded focus:outline-none text-custom-gray focus:border-custom-black"
            autocomplete="email"
        >

        <label for="password" class="block mb-2 font-medium text-custom-gray">Parole</label>
        <input 
            type="password"
            name="password"
            class="block w-full mb-4 px-4 py-2 border border-gray-400 rounded focus:outline-none text-custom-gray focus:border-custom-black"
            autocomplete="current-password"
        >

        <button type="submit" class="w-full bg-custom-lightyellow text-custom-black py-2 rounded hover:bg-custom-darkyellow transition font-semibold mt-2 cursor-pointer">Ienākt</button>
        
        <!-- Validation ERRORS -->
        @if ($errors->any())
            <ul class="rounded px-4 py-2 mt-4 bg-red-100">
                @foreach ($errors->all() as $error)
                    <li class="my-2 text-red-500">{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <div class="text-center mt-4">
            <div class="mb-2">
                <a href="{{ route('password.request') }}" class="text-custom-darkyellow font-semibold hover:underline">Aizmirsta parole?</a>
            </div>
            <div>
                <span class="text-custom-gray">Nav konta?</span>
                <a href="{{ route('show.register') }}" class="text-custom-darkyellow font-semibold hover:underline">Izveidot kontu</a>
            </div>
        </div>
        
    </form>
</div>
@endsection