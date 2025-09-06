<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded mb-4 shadow text-center font-semibold">
                    {{ session('success') }}
                </div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
    <div style="padding: 20px; background: white; text-align: center; margin-top: 20px;">
        <a href="{{ route('driver.hire.form') }}"
            style="background: #2563eb; color: #fff; font-weight: bold; font-size: 1.15em; 
                padding: 14px 36px; border-radius: 8px; text-decoration: none; 
                box-shadow: 0 1px 4px rgba(0,0,0,0.06); display: inline-block;">
            Hire a Driver
        </a>
    </div>
</x-app-layout>
