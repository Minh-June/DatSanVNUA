<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        // XĂ¡c thá»±c dá»¯ liá»‡u Ä‘áº§u vĂ o
        $validated = $request->validated();

        // Táº¡o ngÆ°á»i dĂ¹ng má»›i vĂ o báº£ng 'users'
        User::create([
            'username'  => $validated['username'],
            'password'  => Hash::make($validated['password']),
            'role'      => 1, // Máº·c Ä‘á»‹nh lĂ  ngÆ°á»i dĂ¹ng thÆ°á»ng
            'fullname'  => $validated['fullname'],
            'gender'    => $validated['gender'],
            'birthdate' => $validated['birthdate'],
            'phonenb'   => $validated['phonenb'],
            'email'     => $validated['email'],
        ]);

        // Kiá»ƒm tra náº¿u user hiá»‡n táº¡i lĂ  admin (role = 0)
        if (Auth::check() && Auth::user()->role == 0) {
            return redirect()->route('quan-ly-nguoi-dung')->with('success', 'ThĂªm ngÆ°á»i dĂ¹ng thĂ nh cĂ´ng!');
        }

        // Náº¿u khĂ´ng pháº£i admin thĂ¬ redirect vá» Ä‘Äƒng nháº­p
        return redirect()->route('dang-nhap')->with('success', 'ÄÄƒng kĂ½ thĂ nh cĂ´ng!');
    }
}
