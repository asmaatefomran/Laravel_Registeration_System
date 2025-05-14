@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container">
    <h2>Register in the Form</h2>

    @if(session('success'))
    <div class="successMessage">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('index.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return submitForm(event)">
        @csrf

        <div>
            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" value="{{ old('fullname') }}" required>
            @error('fullname')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}" required>
            <span id="usernameValidation"></span>
            @error('username')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="whatsapp_number">WhatsApp Number</label>
            <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number') }}" id="whatsappInput">
            <button type="button" onclick="validateWhatsApp()" id="validateWhatsappBtn">Validate WhatsApp</button>
            <span class="error" id="whatsappErr"></span>
        </div>

        <div>
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            <span id="emailValidation"></span>
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required>
            @error('phone')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="address">Address</label>
            <input type="text" id="address" name="address" value="{{ old('address') }}" required>
            @error('address')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="image">Upload Image</label>
            <input type="file" id="image" name="image">
            @error('image')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="password-container">
            <div class="password-field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <div class="show-password">
                    <input type="checkbox" id="password_checkbox" onclick="togglePassword()"> Show Password
                </div>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="password-container">
            <div class="password-field">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
                <div class="show-password">
                    <input type="checkbox" id="password_confirmation_checkbox" onclick="toggleConfirmPassword()"> Show Password
                </div>
            </div>
        </div>

        <div id="message">
            <h3>Password must contain the following:</h3>
            <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
            <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
            <p id="number" class="invalid">A <b>number</b></p>
            <p id="length" class="invalid">Minimum <b>8 characters</b></p>
        </div>

        <div id="global-error" class="error" style="display: none; color: red;"></div>

        <button type="submit" class="submit-button">Submit</button>
    </form>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection