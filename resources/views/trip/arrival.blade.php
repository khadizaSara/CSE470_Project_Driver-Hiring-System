<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Trip Completed
        </h2>
    </x-slot>

    <div class="max-w-md mx-auto mt-8 p-6 bg-white shadow rounded">
        <p class="mb-4 font-semibold text-lg">You have arrived at your destination</p>
        <p class="mb-6">
            Payable Amount: <strong>{{ $booking->fare !== null ? $booking->fare . ' ৳' : 'N/A' }}</strong>
        </p>

        <form method="POST" action="{{ route('trip.payment.confirm', $booking->id) }}">
            @csrf
            <label for="payment_method" class="block font-semibold mb-2">Choose Payment Method</label>
            <select name="payment_method" id="payment_method" required class="block w-full border rounded p-2 mb-4">
                <option value="" disabled selected>Select Payment Method</option>
                <option value="credit_card">Credit Card</option>
                <option value="bkash">bKash</option>
                <option value="cash">Cash</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Confirm Payment
            </button>
        </form>
    </div>
</x-app-layout>
