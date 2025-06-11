@extends('layouts.admin')

@section('title', 'ThĂªm sĂ¢n')

@section('content')
    <!-- Hiá»ƒn thá»‹ thĂ´ng bĂ¡o -->
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    <!-- Hiá»ƒn thá»‹ thĂ´ng bĂ¡o lá»—i -->
    @if ($errors->any())
        <script>
            alert("{{ $errors->first() }}");
        </script>
    @endif

    <h3>ThĂªm sĂ¢n má»›i</h3>

    <!-- Form thĂªm sĂ¢n má»›i -->
    <div class="adminedit">
        <form action="{{ route('luu-san') }}" method="POST">
            @csrf
            <label for="type_id">Thá»ƒ loáº¡i sĂ¢n:</label>
            <select id="type_id" name="type_id" required>
                <option value="">Chá»n loáº¡i sĂ¢n</option>
                @foreach($types as $type)
                    <option value="{{ $type->type_id }}">{{ $type->name }}</option>
                @endforeach
            </select>
            <br>
            <label for="name">TĂªn sĂ¢n:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <button class="update-btn" type="submit">LÆ°u thĂ´ng tin sĂ¢n</button>
        </form>
    </div>
@endsection
