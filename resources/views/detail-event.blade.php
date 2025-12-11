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

    @if(session('error'))
        <div id="error-alert" class="bg-red-200 border border-red-200 text-red-700 px-4 py-3 text-[14px] font-semibold rounded-2xl relative mb-4 transition-opacity duration-500" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        <script>
            setTimeout(() => {
                const alert = document.getElementById('error-alert');
                if (alert) {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            }, 5000);
        </script>
    @endif

    <style>
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
    </style>

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <a href="{{ route('discover') }}" class="inline-flex items-center text-[#01044e] hover:text-[#01044e] font-bold text-[16px] mb-8">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Events
        </a>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
            @if($event->image)
                <img src="{{ asset($event->image) }}" alt="{{ $event->name }}" class="w-full h-64 object-cover">
            @else
                <div class="w-full h-64 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                    <span class="text-white text-4xl font-bold">{{ substr($event->name, 0, 1) }}</span>
                </div>
            @endif
            
            <div class="p-6">
                <h1 class="font-bold text-[24px] mb-4">{{ $event->title }}</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-12">
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-[14px] font-semibold">{{ \Carbon\Carbon::parse($event->date)->format('F j, Y') }}</span>
                    </div>
                    
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-[14px] font-semibold">{{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}</span>
                    </div>
                    
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-[14px] font-semibold">{{ $event->location }}</span>
                    </div>
                    
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-[14px] font-semibold">{{ $event->host->name }}</span>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-[18px] mb-3">About This Event</h2>
                    <p class="text-[14px] leading-relaxed">{{ $event->description }}</p>
                </div>
                
                <div class="flex gap-4 mt-6">
                    @if($isHost)
                        <button type="button" onclick="openInviteModal()" class="flex items-center justify-center flex-1 bg-[#01044e] hover:opacity-80 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Invite User
                        </button>
                    @elseif($isRegistered)
                        <button type="button" disabled class="flex items-center justify-center flex-1 bg-gray-300 text-gray-500 font-semibold py-3 px-6 rounded-lg cursor-not-allowed">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Already Registered
                        </button>
                    @else
                        <form action="{{ route('invititation.register', $event->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-[#01044e] hover:opacity-80 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                                Register Now
                            </button>
                        </form>
                    @endif
                    
                    <button class="bg-[#622733] hover:opacity-80 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="invite-modal" class="hidden fixed inset-0 z-40 items-center justify-center bg-black/40">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 p-6 relative">
            <h3 class="text-xl font-bold mb-4">Invite User to Event</h3>
            <form method="POST" action="{{ route('events.sendInvite', $event->id) }}">
                @csrf
                <div class="mb-4">
                    <label for="invite-email">User Email</label>
                    <input type="email" id="invite-email" name="email" placeholder="Enter user email" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#01044e]" required>
                    @error('email')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeInviteModal()" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 font-semibold text-[14px] hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-[#01044e] text-white font-semibold text-[14px] hover:opacity-80">Send Invite</button>
                </div>
            </form>
            <button type="button" onclick="closeInviteModal()" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600">✕</button>
        </div>
    </div>

    <script>
        const inviteModal = document.getElementById('invite-modal');

        function openInviteModal() {
            inviteModal.classList.remove('hidden');
            inviteModal.classList.add('flex');
        }

        function closeInviteModal() {
            inviteModal.classList.add('hidden');
            inviteModal.classList.remove('flex');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const shareButton = document.querySelector('button[class*="bg-[#622733]"]');
            
            if (shareButton) {
                shareButton.addEventListener('click', function() {
                    const url = window.location.href;
                    
                    navigator.clipboard.writeText(url).then(function() {
                        // Change button text temporarily
                        const originalHTML = shareButton.innerHTML;
                        shareButton.innerHTML = '<span class="text-sm">Link Copied!</span>';
                        
                        setTimeout(function() {
                            shareButton.innerHTML = originalHTML;
                        }, 2000);
                    }).catch(function(err) {
                        console.error('Failed to copy: ', err);
                        alert('Failed to copy link to clipboard');
                    });
                });
            }
        });
    </script>

@endsection