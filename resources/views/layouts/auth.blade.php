<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ĐẶT SÂN VNUA</title>
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome-free-6.5.2/css/all.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

    <style>
        body {
            background-image: url('{{ asset('image/login.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
</head>
<body>
    <main>
        @yield('content')
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-password').forEach(function(icon) {

            const passwordInput = icon.previousElementSibling;
            if (!passwordInput) return;

            // đặt icon ban đầu là fa-eye-slash (mắt đóng = đang ẩn mật khẩu)
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');

            icon.addEventListener('click', function () {

                if (passwordInput.type === 'password') {
                    // hiện mật khẩu
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');     // mắt mở
                } else {
                    // ẩn mật khẩu
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash'); // mắt đóng
                }
            });
        });
    });
    </script>
</body>
</html>
