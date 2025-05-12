<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user_info;
use Illuminate\Support\Facades\Hash;

class UserInfoController extends Controller
{
    public function create()
    {
        return view('index');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:user_infos',
            'email' => 'required|email|unique:user_infos',
            'phone' => 'required|string|max:15',
            'whatsapp_number' => 'nullable|string|max:15',
            'address' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
        $whatsapp_number = $validated['whatsapp_number'] ?? null;
        $user_info->whatsapp_number = $whatsapp_number;
        $user_info->address = $validated['address'];
        // $user_info->password = bcrypt($validated['password']);
        $user_info->password = Hash::make($validated['password']);
        $user_info->image = $imagePath;
        $user_info->save();


        return redirect()->route('index')->with('success', 'Registration successful!');
    }
}
