@extends('layouts.admin')

@section('title', 'ThĂªm khung giá» sĂ¢n')

@section('content')
    <!-- Hiá»ƒn thá»‹ thĂ´ng bĂ¡o -->
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    <!-- Hiá»ƒn thá»‹ thĂ´ng bĂ¡o lá»—i -->
    @if(session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif

    <h3>ThĂªm khung giá» cho thuĂª</h3>

    <!-- Form thĂªm khung giá» -->
    <div class="adminedit">
        <form action="{{ route('luu-thoi-gian-san') }}" method="POST">
            @csrf

            <label for="yard_id">Chá»n sĂ¢n:</label>
            <select id="yard_id" name="yard_id" required>
                <option value="">Chá»n sĂ¢n</option>
                @foreach($yards as $yard)
                    <option value="{{ $yard->yard_id }}">{{ $yard->name }}</option>
                @endforeach
            </select>
            <br>

            <label for="time">Khung giá»:</label>
            <input type="text" id="time" name="time" required pattern="\d{2}:\d{2}\s*-\s*\d{2}:\d{2}" title="Äá»‹nh dáº¡ng pháº£i lĂ  HH:MM - HH:MM">
            <br>

            <label for="price">GiĂ¡ (VNÄ):</label>
            <input type="number" id="price" name="price" required min="0" step="1000">
            <br>

            <label for="date">NgĂ y Ă¡p dá»¥ng:</label>
            <input type="date" id="date" name="date" required>
            <br>

            <button class="update-btn" type="submit">LÆ°u khung giá»</button>
        </form>
    </div>
@endsection
