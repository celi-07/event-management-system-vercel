@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <div class="mb-4">
        <h2 class="text-3xl font-bold mb-1">Discover Events</h2>
        <p class="text-[14px] text-gray-600">Browse upcoming events near you.</p>
    </div>

    @if($events->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                @php
                    $image = $event->image ?? ($event->image_url ?? null);
                    $imageUrl = $image ? (Str::startsWith($image, ['http://','https://']) ? $image : asset($image)) : '';
                    $start = $event->start_date ?? $event->date ?? null;
                @endphp

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col h-full">
                    @if($imageUrl)
                        <img src="{{ $imageUrl }}" class="w-full h-[200px] object-cover" alt="{{ $event->title ?? 'Event image' }}">
                    @else
                        <div class="w-full h-[200px] bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                            <span class="text-white text-4xl font-bold">{{ substr($event->title ?? 'E', 0, 1) }}</span>
                        </div>
                    @endif

                    <div class="p-4 flex flex-col flex-grow">
                        <h5 class="font-bold text-[20px] mb-3">{{ $event->title ?? 'Untitled Event' }}</h5>
                        
                        <div class="text-gray-500 text-sm mb-3 space-y-1">
                            <div class="font-semibold text-[14px]">
                                <i class="bi bi-calendar-event mr-1"></i>
                                {{ $start ? (is_string($start) ? $start : \Carbon\Carbon::parse($start)->format('M d, Y')) : 'TBA' }}
                            </div>
                            @if(!empty($event->location))
                                <div class="font-semibold text-[14px]">
                                    <i class="bi bi-geo-alt mr-1"></i>
                                    {{ $event->location }}
                                </div>
                            @endif
                        </div>

                        <p class="text-gray-500 flex-grow text-[14px] font-light mb-4">
                            {{ \Illuminate\Support\Str::limit($event->description ?? $event->excerpt ?? '', 160) }}
                        </p>

                        <div class="mt-auto bg-[#01044e] text-center py-2 rounded-lg hover:opacity-80 transition">
                            <a href="{{ route('events.show', $event->id) }}" class="block w-full font-semibold text-[15px] no-underline text-white">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-center">
            @if(method_exists($events, 'links'))
                {{ $events->links() }}
            @endif
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h4 class="text-xl font-semibold mb-2">No events found</h4>
            <p class="text-gray-500 mb-4">Try adjusting your search or check back later.</p>
            @if(auth()->user()?->is_organizer)
                <a href="{{ route('create.events') }}" class="inline-block bg-[#01044e] text-white px-6 py-2 rounded-lg font-semibold hover:opacity-80 transition">Create an event</a>
            @endif
        </div>
    @endif

@endsection