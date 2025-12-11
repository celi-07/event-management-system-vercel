@extends('layouts.app')

@section('content')

    @if(session('success'))
        <div id="success-alert" class="bg-green-200 border border-green-200 text-green-700 px-4 py-3 text-[14px] font-semibold rounded-2xl relative mb-4 transition-opacity duration-500" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        
        <script>
            setTimeout(() => {
                const alert = document.getElementById('success-alert');
                if (alert) {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            }, 5000);
        </script>
    @endif

    <style>
        input:not(.noglobal) {
            background-color: #FFFFFF;
            border: none;
            padding: 8px 15px;
            margin: 8px 0;
            width: 100%;
            box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
            font-size: small;
        }

        label {
            font-weight: 300;
            color: black;
        }
    </style>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold mb-1">Profile Settings</h1>
                <p class="text-[14px] text-gray-600">Manage your account information</p>
            </div>

            <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
                <h2 class="font-bold text-[18px] mb-4">Profile Image</h2>
                <form action="{{ route('profile.image.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="flex items-center space-x-6">
                        <div class="shrink-0">
                            <img class="h-24 w-24 object-cover rounded-full ring-2 ring-gray-300" 
                                src="{{ Auth::user()->profile_image ? asset(Auth::user()->profile_image) : asset('images/account.png') }}" 
                                alt="Profile picture">
                        </div>
                        <div class="flex-1">
                            <label class="block">
                                {{-- <span class="sr-only">Choose profile photo</span> --}}
                                <input type="file" name="profile_image" accept="image/*"
                                    class="noglobal block w-full text-[14px] text-[#000]
                                    bg-gray-200 rounded-md
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-[12px] file:font-semibold
                                    file:bg-[#622733] file:text-[#fff]
                                    hover:file:bg-indigo-50 hover:file:text-[#000] cursor-pointer">
                            </label>
                            @error('profile_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" 
                                class="px-4 py-2 font-semibold bg-[#01044e] text-[14px] text-white rounded-md hover:bg-indigo-50 hover:text-[#000] focus:outline-none focus:ring-2 focus:ring-[#01044e]">
                            Upload
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
                <h2 class="font-bold text-[18px] mb-4">Personal Information</h2>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium">Name</label>
                            <input type="text" name="name" id="name" 
                                value="{{ old('name', Auth::user()->name) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium">Email</label>
                            <input type="email" name="email" id="email" 
                                value="{{ old('email', Auth::user()->email) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-4">
                            <button type="submit" 
                                    class="px-6 py-2 font-semibold text-[14px] bg-[#01044e] text-white rounded-md hover:bg-indigo-50 hover:text-[#000] focus:outline-none focus:ring-2 focus:ring-[#01044e]">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
                <h2 class="font-bold text-[18px] mb-4">Change Password</h2>
                <form action="{{ route('profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium">Current Password</label>
                            <input type="password" name="current_password" id="current_password" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium">New Password</label>
                            <input type="password" name="password" id="password" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('password_confirmation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-4">
                            <button type="submit" 
                                    class="px-6 py-2 font-semibold text-[14px] bg-[#01044e] text-white rounded-md hover:bg-indigo-50 hover:text-[#000] focus:outline-none focus:ring-2 focus:ring-[#01044e]">
                                Update Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="mt-12 flex justify-center">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="flex items-center justify-center px-12 py-2 font-semibold text-[16px] bg-[#622733] text-white rounded-md hover:bg-[#01044e] focus:outline-none focus:ring-2 focus:ring-[#01044e]">
                            <img src="{{ asset('images/logout.svg') }}" class="inline h-[16px] w-[16px] mr-2" alt="Logout Icon" />
                            <span>Logout</span>
                    </button>
                </form>
            </div>

            @if(session('success'))
                <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-md shadow-lg">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>

@endsection