@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-12 p-8 bg-white shadow rounded">
    <h2 class="text-2xl font-semibold mb-6 text-center">Rate and Review Driver <span class="text-blue-600">{{ $driver->name }}</span></h2>
    
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-700 rounded">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('review.submit') }}" method="POST">
        @csrf
        <input type="hidden" name="driver_id" value="{{ $driver->id }}">
        <input type="hidden" name="booking_id" value="{{ $booking->id }}">

        <div class="mb-4">
            <label for="rating" class="font-semibold block mb-2">Rating (1 to 5)</label>
            <select id="rating" name="rating" required class="block w-full border rounded p-2 @error('rating') border-red-400 @enderror">
                <option value="">Select rating</option>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
            @error('rating')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="review" class="block font-semibold mb-2">Review (optional)</label>
            <textarea id="review" name="review" rows="4" class="block w-full border rounded p-2 @error('review') border-red-400 @enderror">{{ old('review') }}</textarea>
            @error('review')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition">Submit Rating & Review</button>
    </form>
</div>
@endsection
