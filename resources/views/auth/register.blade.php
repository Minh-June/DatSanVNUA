@extends('layouts.auth')

@section('title', 'ÄÄƒng KĂ½')

@section('content')
    <!-- Hiá»ƒn thá»‹ thĂ´ng bĂ¡o thĂ nh cĂ´ng -->
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

    <div class="container-access" id="signIn">
        <h1 class="form-title">ÄÄƒng KĂ½</h1>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>CĂ³ lá»—i xáº£y ra !</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <br>
        <form method="post" action="{{ route('dang-ky') }}">
            @csrf            
            <div class="input-group">
                <i class="fa-solid fa-user"></i>
                <input type="text" id="fullname" name="fullname" placeholder="Há» vĂ  tĂªn" required>
                <label class="label-access" for="fullname"></label>
            </div>
        
            <div class="input-group">
                <i class="fa-solid fa-genderless input-group-icon"></i>
                <label class="input-group-select" for="gender">Giá»›i tĂ­nh:</label>
                <select class="login-time-select" id="gender" name="gender" required>
                    <option value="" disabled selected>Chá»n</option>
                    <option value="Nam">Nam</option>
                    <option value="Ná»¯">Ná»¯</option>
                    <option value="KhĂ¡c">KhĂ¡c</option>
                </select>
            </div>
        
            <div class="input-group">
                <i class="fa-solid fa-calendar input-group-icon"></i>
                <label class="input-group-select" for="birthdate">NgĂ y sinh:</label>
                <input class="login-time-select" type="date" id="birthdate" name="birthdate" required>
            </div>
        
            <div class="input-group">
                <i class="fa-solid fa-phone"></i>
                <input type="text" id="phonenb" name="phonenb" placeholder="Sá»‘ Ä‘iá»‡n thoáº¡i" required>
                <label class="label-access" for="phonenb"></label>
            </div>
        
            <div class="input-group">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" id="email" name="email" placeholder="Email" required>
                <label class="label-access" for="email"></label>
            </div>
        
            <div class="input-group">
                <i class="fa-regular fa-user"></i>
                <input type="text" name="username" id="username" placeholder="TĂªn ngÆ°á»i dĂ¹ng" required>
                <label class="label-access" for="username"></label>
            </div>
        
            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Máº­t kháº©u" required>
                <label class="label-access" for="password"></label>
            </div>
        
            <input type="submit" class="index-btn" value="ÄÄƒng KĂ½" name="btnDangky">
        </form>        

        <div class="links">
            <p>Báº¡n Ä‘Ă£ cĂ³ tĂ i khoáº£n ?</p>
            <a href="{{ route('dang-nhap') }}"><button id="signUpButton">ÄÄƒng Nháº­p</button></a>
        </div>

    </div>
@endsection
