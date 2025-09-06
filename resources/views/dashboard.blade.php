<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded mb-6 shadow text-center font-semibold text-lg">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $promo = Auth::user()->promocodes()->where('is_used', false)->latest()->first();
            @endphp

            @if ($promo)
                <div class="bg-gradient-to-r from-green-200 via-green-300 to-green-200 border border-green-400 rounded-lg p-6 mb-8 shadow-lg max-w-md mx-auto text-center">
                    <h3 class="text-xl font-bold text-green-900 mb-2">Your Promo Code</h3>
                    <p class="text-3xl font-extrabold text-green-800 tracking-widest mb-2">{{ $promo->code }}</p>
                    <p class="text-lg text-green-700 mb-4">Discount: <span class="font-semibold">{{ $promo->discount_percentage }}%</span></p>
                    <p class="text-green-900 font-medium">Use this code on the booking page to get your discount!</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center font-semibold text-lg">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    <div style="padding: 20px; background: white; text-align: center; margin-top: 20px;">
        <a href="{{ route('driver.hire.form') }}"
            class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-md transition duration-300">
            Hire a Driver
        </a>
    </div>
</x-app-layout>
