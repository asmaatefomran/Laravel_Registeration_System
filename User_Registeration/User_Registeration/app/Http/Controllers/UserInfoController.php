<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user_info;
use Illuminate\Support\Facades\Hash;

use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

class UserInfoController extends Controller
{
    public function create()
    {
        return view('index');
    }


    public function store(Request $request)
{
    $validated = $request->validate([
        'fullname' => [
            'required',
            'regex:/^[a-zA-Z\s\.\'\-]+$/'
        ],
        'username' => [
            'required',
            'regex:/^\S+$/', // No spaces
            'max:255',
            'unique:user_infos'
        ],
        'email' => [
            'required',
            'email',
            'unique:user_infos'
        ],
        'phone' => [
            'required',
            'regex:/^[0-9]{11}$/'
        ],
        'whatsapp_number' => [
            'required',
            'regex:/^[0-9]{11}$/'
        ],
        'address' => [
            'required',
            'string'
        ],
        'password' => [
            'required',
            'confirmed',
            'regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/'
        ],
        'image' => [
            'required',
            'image',
            'mimes:jpeg,png,jpg,gif',
            'max:2048'
        ],
    ], [
        'fullname.regex' => 'Full name may only contain letters, spaces, hyphens, apostrophes, and dots.',
        'username.regex' => 'Username must not contain spaces.',
        'phone.regex' => 'Phone number must be exactly 11 digits.',
        'whatsapp_number.regex' => 'WhatsApp number must be exactly 11 digits.',
        'password.regex' => 'Password must be at least 8 characters and contain at least one number, one uppercase, and one lowercase letter.',
        'image.required' => 'Image is required.',
        'image.image' => 'Only image files are allowed.',
        'image.mimes' => 'Only JPG, JPEG, PNG, and GIF formats are allowed.',
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('uploads', 'public');
    }

    $user_info = new user_info();
    $user_info->fullname = $validated['fullname'];
    $user_info->username = $validated['username'];
    $user_info->email = $validated['email'];
    $user_info->phone = $validated['phone'];
    $user_info->whatsapp_number = $validated['whatsapp_number'];
    $user_info->address = $validated['address'];
    $user_info->password = Hash::make($validated['password']);
    $user_info->image = $imagePath;
    $user_info->save();

    Mail::to($user_info->email)->send(new WelcomeEmail($user_info->username));


    return redirect()->route('index')->with('success', 'Registration successful!');    }

    public function validateUsername(Request $request)
{
    $valid = !user_info::where('username', $request->username)->exists();
    
    return response()->json([
        'valid' => $valid,
        'message' => $valid ? 'Username available' : 'Username already taken'
    ]);
}

public function validateEmail(Request $request)
{
    $valid = !user_info::where('email', $request->email)->exists();
    
    return response()->json([
        'valid' => $valid,
        'message' => $valid ? 'Email available' : 'Email already registered'
    ]);
}
}
