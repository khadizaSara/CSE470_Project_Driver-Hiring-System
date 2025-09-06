<form action="{{ route('driver.saveReview', ['driverId' => $driver->id]) }}" method="POST">
    @csrf
    <label>Rate Driver (1-5):</label>
    <select name="rating" required>
        @for ($i = 1; $i <= 5; $i++)
            <option value="{{ $i }}">{{ $i }}</option>
        @endfor
    </select>
    <label>Review (optional, max 50 words):</label>
    <textarea name="review" maxlength="255"></textarea>
    <button type="submit">Submit</button>
</form>
