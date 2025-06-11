@extends('layouts.admin')

@section('title', 'Cáº­p nháº­t khung giá» sĂ¢n')

@section('content')
    <!-- Hiá»ƒn thá»‹ thĂ´ng bĂ¡o -->
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    @if(session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif

    <h3>Cáº­p nháº­t khung giá»</h3>

    <!-- Form cáº­p nháº­t khung giá» -->
    <div class="adminedit">
        <form action="{{ route('update.time', ['time_id' => $time->time_id]) }}" method="POST">
            @csrf

            <label for="yard_id">Chá»n sĂ¢n:</label>
            <select id="yard_id" name="yard_id" required>
                @foreach($yards as $yard)
                    <option value="{{ $yard->yard_id }}" {{ $yard->yard_id == $time->yard_id ? 'selected' : '' }}>
                        {{ $yard->name }}
                    </option>
                @endforeach
            </select>
            <br>

            <label for="time">Khung giá»:</label>
            <input type="text" id="time" name="time" required pattern="\d{2}:\d{2}\s*-\s*\d{2}:\d{2}" title="Äá»‹nh dáº¡ng pháº£i lĂ  HH:MM - HH:MM">            <br>

            <label for="price">GiĂ¡ (VNÄ):</label>
            <input type="number" id="price" name="price" value="{{ $time->price }}" required min="0">
            <br>

            <label for="date">NgĂ y Ă¡p dá»¥ng:</label>
            <input type="date" id="date" name="date" value="{{ $time->date }}" required>
            <br>

            <button class="update-btn" type="submit">Cáº­p nháº­t khung giá»</button>
        </form>
    </div>
@endsection
