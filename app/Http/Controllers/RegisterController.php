<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{

    public function show()
    {
        return view('auth.register');
    }

 
    public function register(Request $request) 
    {
        $user = User::create($request->validated());

        auth()->login($user);

        return redirect('/')->with('success', "Account successfully registered.");
    }
}