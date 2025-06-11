@extends('layouts.auth')

@section('title', 'ÄÄƒng Nháº­p')

@section('content')
    <div class="container-access" id="signIn">
        <h1 class="form-title">ÄÄƒng Nháº­p</h1>

        <form method="post" action="{{ route('dang-nhap') }}">
            @csrf
            @method('post')

            <div class="input-group">
                <i class="fa-solid fa-envelope"></i>
                <input type="text" name="username" id="username" placeholder="TĂªn ngÆ°á»i dĂ¹ng" required>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Máº­t kháº©u" required>
            </div>

            {{-- Hiá»ƒn thá»‹ lá»—i ngay trĂªn nĂºt Ä‘Äƒng nháº­p --}}
            @if ($errors->any())
                <div class="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <input type="submit" class="index-btn" value="ÄÄƒng Nháº­p">
        </form>

        <div class="links">
            <p>Báº¡n chÆ°a cĂ³ tĂ i khoáº£n?</p>
            <a href="{{ route('dang-ky') }}"><button id="signUpButton">ÄÄƒng KĂ½</button></a>
        </div>
    </div>
@endsection
