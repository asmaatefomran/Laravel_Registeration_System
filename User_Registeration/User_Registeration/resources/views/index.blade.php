@extends('layouts.app')

@section('title', __('messages.register'))

@section('content')
<div class="container">
    <h2>{{ __('messages.heading') }}</h2>

    @if(session('success'))
    <div class="successMessage">{{ __('messages.success') }}</div>
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
            <label for="fullname">{{ __('messages.name') }}</label>
            <input type="text" id="fullname" name="fullname" value="{{ old('fullname') }}" required>
            @error('fullname')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="username">{{ __('messages.username') }}</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}" required>
            <span id="usernameValidation"></span>
            @error('username')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="whatsapp_number">{{ __('messages.WhatsApp') }}</label>
            <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number') }}" id="whatsappInput">
            <button type="button" onclick="validateWhatsApp()" id="validateWhatsappBtn">{{ __('messages.validate_whatsapp') ?? 'Validate WhatsApp' }}</button>
            <span class="error" id="whatsappErr"></span>
        </div>

        <div>
            <label for="email">{{ __('messages.email') }}</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            <span id="emailValidation"></span>
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="phone">{{ __('messages.phone') }}</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required>
            @error('phone')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="address">{{ __('messages.address') }}</label>
            <input type="text" id="address" name="address" value="{{ old('address') }}" required>
            @error('address')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="image">{{ __('messages.image') }}</label>
            <input type="file" id="image" name="image">
            @error('image')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="password-container">
            <div class="password-field">
                <label for="password">{{ __('messages.password') }}</label>
                <input type="password" id="password" name="password" required>
                <div class="show-password">
                    <input type="checkbox" id="password_checkbox" onclick="togglePassword()"> {{ __('messages.show_password') }}
                </div>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="password-container">
            <div class="password-field">
                <label for="password_confirmation">{{ __('messages.confirm_password') }}</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
                <div class="show-password">
                    <input type="checkbox" id="password_confirmation_checkbox" onclick="toggleConfirmPassword()"> {{ __('messages.show_password') }}
                </div>
            </div>
        </div>

        <div id="message">
            <h3>{{ __('messages.password_requirements_heading') }}</h3>
            <p id="letter" class="invalid">{{ __('messages.password_lowercase') }}</p>
            <p id="capital" class="invalid">{{ __('messages.password_uppercase') }}</p>
            <p id="number" class="invalid">{{ __('messages.password_number') }}</p>
            <p id="length" class="invalid">{{ __('messages.password_length') ?? }}</p>
        </div>

        <div id="global-error" class="error" style="display: none; color: red;"></div>

        <button type="submit" class="submit-button">{{ __('messages.submit') }}</button>
    </form>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
