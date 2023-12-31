<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register.index', [
            "title" => "Register"
        ]);
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'username' => 'required|min:4|max:255|unique:users',
            'email' => 'required|email:dns|unique:users',
            'password' => 'required|min:5|max:255'
        ]);

        $validateData['password'] = bcrypt($validateData['password']);
        
        User::create($validateData);

        // $request->session()->flash('success', 'Registration successful!');

        return redirect('/login')->with('success', 'Registration successful!');
    }

}
