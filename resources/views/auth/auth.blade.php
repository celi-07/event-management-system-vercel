@extends('layouts.app')

@section('content')

    <style>
        * {
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
        }

        p {
            font-size: 14px;
            font-weight: 100;
            line-height: 20px;
            letter-spacing: 0.5px;
            margin: 20px 0 30px;
        }

        h1 {
            font-weight: bold;
            margin: 0;
            letter-spacing: 15px;
            font-size: 50px;
            color: #430000;
            text-shadow: 1px 1px 5px #430000;
        }

        h1#form-register-title {
            color: #4F47E4;
            text-shadow: 1px 1px 5px #4F47E4;
        }

        span {
            font-size: 16px;
            margin-top: 10px;
            color: #00000078;
        }

        a {
            color: #333;
            font-size: 14px;
            text-decoration: none;
            margin: 15px 0;
        }

        .auth-button {
            border-radius: 8px;
            border: 1px solid #622733;
            background-color: #622733;
            color: #FFFFFF;
            font-size: 12px;
            font-weight: 700;
            padding: 10px 40px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: transform 80ms ease-in;
            box-shadow: #622733 0px 2px 8px 0px;
            margin: 15px 0;
        }

        button#form-register-button {
            border: 1px solid #4F47E4;
            background-color: #4F47E4;
            box-shadow: #4F47E4 0px 2px 8px 0px;
        }

        button:active {
            transform: scale(0.95);
        }

        button:focus {
            outline: none;
        }

        button.ghost {
            background-color: transparent;
            border-color: #FFFFFF;
        }

        form {
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 60px;
            height: 100%;
            text-align: center;
        }

        label {
            font-weight: 300;
            color: black;
        }

        input {
            background-color: #FFFFFF;
            border: none;
            padding: 8px 15px;
            margin: 8px 0;
            width: 100%;
            box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
            font-size: small;
        }

        .container {
            background-color: #FFFFFF;
            border-radius: 10px;
            box-shadow: 0 14px 28px rgba(0,0,0,0.25), 
                    0 10px 10px rgba(0,0,0,0.22);
            position: relative;
            overflow: hidden;
            width: 1100px;
            max-width: 100%;
            min-height: 750px;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        .form-input {
            display: flex;
            width: 100%;
            flex-direction: column;
            align-items: start;
            margin: 8px 0;
        }

        .form-input label {
            margin: 0;
        }

        .form-name-input {
            display: flex;
            width: 100%;
            flex-direction: row;
            align-items: start;
            gap: 10px;
        }

        .sign-in-container {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
            background: none;
        }

        .container.right-panel-active .sign-up-container {
            transform: translateX(100%);
        }

        .sign-up-container {
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .container.right-panel-active .sign-in-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: show 0.6s cubic-bezier(0.1, 10, 0.9, -5);
        }

        @keyframes show {
            0%, 49.99% {
                opacity: 0;
                z-index: 1;
            }
            
            50%, 100% {
                opacity: 1;
                z-index: 5;
            }
        }

        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: transform 0.6s ease-in-out;
            z-index: 100;
        }

        .container.right-panel-active .overlay-container{
            transform: translateX(-100%);
        }

        .overlay {
            background-color: #632933;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 0 0;
            color: #FFFFFF;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .container.right-panel-active .overlay {
            transform: translateX(50%);
            background-color: #4F47E4;
        }

        .overlay-panel {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .overlay-panel img{
            width: 100%;
            height: auto;
        }

        .overlay-left {
            transform: translateX(-20%);
            background-color: #632933;
        }

        .container.right-panel-active .overlay-left {
            transform: translateX(0);
        }

        .overlay-right {
            right: 0;
            transform: translateX(0);
            background-color: #4F47E4;
        }

        .container.right-panel-active .overlay-right {
            transform: translateX(20%);
        }

        .container.no-transition,
        .container.no-transition * {
            transition: none !important;
            animation: none !important;
        }

        .overlay-button {
            cursor: pointer;
            color: #622733;
        }

        .overlay-button#signIn {
            color: #4F47E4;
        }
    </style>

    @php
        $authError = session('auth_error') ?? null;
        $action = is_array($authError) ? ($authError['action'] ?? '') : '';
        $message = is_array($authError) ? ($authError['message'] ?? []) : '';

        if (!$action && $errors->any()) {
            if ($errors->has('first_name') || 
                $errors->has('last_name') || 
                $errors->has('password_confirmation')) {
                $action = 'signUp';
            } else if (old('first_name') !== null || old('last_name') !== null) {
                $action = 'signUp';
            } else {
                $action = 'signIn';
            }
        }
    @endphp
    
    <div class= "container {{ $action === 'signIn' ? 'right-panel-active' : ($action === 'signUp' ? 'left-panel-active' : '') }} {{ $action ? 'no-transition' : '' }}" id="container">
        <div class="form-container sign-in-container">
            <form action="{{ route('login') }}" method="POST">
            @csrf
                <h1>EMS</h1>
                <div class="form-input">
                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="Type your email here..." value="{{ old('email') }}"  required/>
                </div>
                <div class="form-input">
                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Type your password here..." required/>
                </div>
                @if ($action === 'signIn')
                    <div class="w-full mb-4 text-sm text-red-600">
                        <ul class="list-disc list-inside m-0">
                            @if (!empty($message))
                                <li>{{ $message }}</li>
                            @endif

                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            @endif
                        </ul>
                    </div>  
                @endif
                <button type="submit" class="auth-button">Sign In</button>
                <span>Don't have an account? <span class="overlay-button" id="signUp">Sign Up</span></span>
            </form>
        </div>
        
        <div class="form-container sign-up-container">
            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <h1 id="form-register-title">EMS</h1>
                <div class="form-name-input">
                    <div class="form-input">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" placeholder="Type your first name here..." value="{{ old('first_name') }}"  required/>
                    </div>
                    <div class="form-input">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" placeholder="Type your last name here..." value="{{ old('last_name') }}"  required/>
                    </div>
                </div>
                <div class="form-input">
                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="Type your email here..." value="{{ old('email') }}"  required/>
                </div>
                <div class="form-input">
                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Type your password here..." required/>
                </div>
                <div class="form-input">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="Type your password here..." required/>
                </div>
                <div class="my-4 flex items-center">
                    <label class="relative inline-flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" id="is_organizer" name="is_organizer" value="1" class="sr-only peer" {{ old('is_organizer') ? 'checked' : '' }}>
                        <div class="relative w-10 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#01044e]"></div>
                        <span class="text-[16px] font-light text-gray-900 leading-none mt-0">Register as organizer</span>
                    </label>
                </div>
                @if ($action === 'signUp')
                    <div class="w-full mb-4 text-sm text-red-600">
                        <ul class="list-disc list-inside m-0">
                            @if (!empty($message))
                                <li>{{ $message }}</li>
                            @endif

                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            @endif
                        </ul>
                    </div>  
                @endif
                <button id="form-register-button" class="auth-button">Sign Up</button>
                <span>Already have an account? <span class="overlay-button" id="signIn">Sign In</span></span>
            </form>
        </div>

        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-right">
                    {{-- <img src="{{ asset('images/register.png') }}" alt="My Image"> --}}
                </div>
                <div class="overlay-panel overlay-left">
                    {{-- <img src="{{ asset('images/login.png') }}" alt="My Image"> --}}
                </div>
            </div>
        </div>
    </div>
@endsection