@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @php
        $isPublished = ($event->status ?? 'Draft') === 'Published';
    @endphp

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
        .form-input {
            display: flex;
            width: 100%;
            flex-direction: column;
            align-items: start;
            margin: 8px 0 16px 0;
        }
        .form-input label {
            margin: 0;
        }
        label {
            font-weight: 300;
            color: black;
        }
        input:not(.noglobal), textarea {
            background-color: #FFFFFF;
            border: none;
            padding: 8px 15px;
            margin: 8px 0;
            width: 100%;
            box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
            font-size: small;
        }
        textarea::placeholder {
            color: #000;
            opacity: 1; /* Firefox needs this */
        }
    </style>

    <div class="max-w-4xl mx-auto py-8">
        <div class="mb-4">
            <h1 class="text-3xl font-bold">Edit Event</h1>
            <p class="text-[14px] text-gray-600">Status: <span class="font-semibold">{{ $event->status }}</span></p>
        </div>

        <div class="bg-white shadow-sm border-0 rounded-2xl">
            <div class="p-8">
                <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-input">
                        <label for="title">Event Title <span class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control @error('title') is-invalid @enderror" 
                            id="title"
                            name="title"
                            value="{{ old('title', $event->title) }}"
                            placeholder="Enter event title"
                            {{ $isPublished ? 'disabled' : '' }}
                            required>
                        @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-input">
                        <label for="date">Event Date <span class="text-danger">*</span></label>
                        <input type="date"
                            class="form-control @error('date') is-invalid @enderror"
                            id="date"
                            name="date"
                            value="{{ old('date', $event->date) }}"
                            {{ $isPublished ? 'disabled' : '' }}
                            required>
                        @error('date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-input">
                        <label for="location">Location <span class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control @error('location') is-invalid @enderror"
                            id="location"
                            name="location"
                            value="{{ old('location', $event->location) }}"
                            placeholder="Enter event location"
                            {{ $isPublished ? 'disabled' : '' }}
                            required>
                        @error('location')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-input">
                        <label for="image">Event Image</label>
                        <input type="file"
                            id="image"
                            name="image"
                            accept="image/*"
                            class="noglobal @error('image') is-invalid @enderror block w-full text-[14px] rounded-md mt-[8px] mb-[2px]
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-[12px] file:font-semibold
                            file:bg-[#622733] file:text-[#fff]
                            {{ $isPublished ? 'bg-[#ebecef] text-black' : 'hover:file:bg-indigo-50 hover:file:text-[#000] cursor-pointer text-[#000] bg-gray-200' }}"
                            {{ $isPublished ? 'disabled' : '' }}>
                        <small class="text-sm fw-300 mt-2">Supported formats: JPG, PNG, GIF (Max 2MB)</small>
                        @if($event->image)
                            <div class="mt-2">
                                <img src="{{ asset($event->image) }}" alt="Current image" style="max-height:120px;" class="rounded shadow-sm">
                            </div>
                        @endif
                        @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-input">
                        <label for="description">Description <span class="text-danger">*</span></label>
                        <textarea class="@error('description') is-invalid @enderror
                                {{ $isPublished ? 'bg-[#ebecef] text-black' : '' }}"
                                id="description"
                                name="description"
                                rows="6"
                                placeholder="Describe your event..."
                                {{ $isPublished ? 'disabled' : '' }}
                                required>{{ old('description', $event->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    @unless($isPublished)
                        <div class="mb-4 flex items-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="publish_now" name="publish_now" value="1" class="sr-only peer">
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#01044e]"></div>
                                <span class="ms-3 text-[16px] font-light text-gray-900 mt-0">Publish this draft</span>
                            </label>
                        </div>
                    @endunless

                    <div class="flex justify-between items-center pt-3">
                        <a href="{{ route('my.events') }}" class="px-5 py-[6px] rounded-lg border border-gray-200 text-gray-700 font-semibold text-[14px] hover:bg-gray-50">
                            Cancel
                        </a>
                        <div class="flex justify-end gap-3 pt-3">
                            @unless($isPublished)
                                <button type="submit" class="px-5 font-semibold text-[14px] bg-[#01044e] text-white rounded-lg hover:opacity-50 py-[6px] border-1 border-[#01044e]">
                                    <i class="bi bi-pencil-square me-2"></i>Save Changes
                                </button>
                            @endunless
                            
                            <button type="button" onclick="deleteEvent()" class="px-5 font-semibold text-[14px] border border-[#622733] text-[#622733] rounded-lg hover:opacity-50 py-[6px]">
                                <i class="bi bi-trash me-2"></i>Delete
                            </button>
                        </div>
                    </div>
                </form>

                <form id="delete-form" action="{{ route('events.destroy', $event->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>

                <script>
                    function deleteEvent() {
                        if (confirm('Are you sure you want to delete this event?')) {
                            document.getElementById('delete-form').submit();
                        }
                    }
                </script>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection