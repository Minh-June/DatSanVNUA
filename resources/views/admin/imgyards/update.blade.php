@extends('layouts.admin')

@section('title', 'Cáº­p nháº­t hĂ¬nh áº£nh sĂ¢n')

@section('content')
    <h3>Cáº­p nháº­t hĂ¬nh áº£nh</h3>

    @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif

    <form method="POST" action="{{ route('cap-nhat-hinh-anh-san', ['image_id' => $image->image_id]) }}" enctype="multipart/form-data">
        @csrf
        <div class="pay-information">
            <div class="admin-img">
                <!-- Hiá»ƒn thá»‹ áº£nh hiá»‡n táº¡i -->
                <img src="{{ asset('storage/' . $image->image) }}" alt="HĂ¬nh áº£nh" class="admin-image">
            </div>
        </div>

        <div class="pay-information">
            <div class="admin-img">
                <h3>{{ $image->yard->name }}</h3>

                <label for="image">Chá»n hĂ¬nh áº£nh má»›i:</label><br><br>
                <input type="file" name="image" id="image"><br>

                @error('image')
                    <div class="error">{{ $message }}</div>
                @enderror

                <button type="submit" class="update-btn">Cáº­p nháº­t</button>
            </div>
        </div>
    </form>                        
@endsection
