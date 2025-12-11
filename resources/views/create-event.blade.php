@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

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
            <h1 class="text-3xl font-bold">Create New Event</h1>
            <p class="text-[14px] text-gray-600">Fill in the details below to create your event</p>
        </div>

        <div class="bg-white shadow-sm border-0 rounded-2xl">
            <div class="p-8">
                <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-input">
                        <label for="title">Event Title <span class="text-danger">*</span></label>
                        <input type="text" 
                                class="@error('title') is-invalid @enderror" 
                                id="title" 
                                name="title" 
                                placeholder="Enter event title"
                                value="{{ old('title') }}"
                                required>
                        @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-input">
                        <label for="date">Event Date <span class="text-danger">*</span></label>
                        <input type="date" 
                                class="@error('date') is-invalid @enderror" 
                                id="date" 
                                name="date"
                                value="{{ old('date') }}"
                                required>
                        @error('date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-input">
                        <label for="location">Location <span class="text-danger">*</span></label>
                        <input type="text" 
                                class="@error('location') is-invalid @enderror" 
                                id="location" 
                                name="location" 
                                placeholder="Enter event location"
                                value="{{ old('location') }}"
                                required>
                        @error('location')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-input">
                        <label for="image">Event Image</label>
                        <input type="file" id="image" name="image" accept="image/*"
                                class="noglobal @error('image') is-invalid @enderror block w-full text-[14px] text-[#000]
                                bg-gray-200 rounded-md mt-[8px] mb-[2px]
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-[12px] file:font-semibold
                                file:bg-[#622733] file:text-[#fff]
                                hover:file:bg-indigo-50 hover:file:text-[#000] cursor-pointer">
                        <span class="text-sm fw-300 mt-2">Supported formats: JPG, PNG, GIF (Max 2MB)</span>
                        @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-input">
                        <label for="description">Description <span class="text-danger">*</span></label>
                        <textarea class="@error('description') is-invalid @enderror" 
                                    id="description" 
                                    name="description" 
                                    rows="6" 
                                    placeholder="Describe your event..."
                                    required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex justify-between items-center pt-3">
                        <a href="{{ route('dashboard') }}" class="px-5 py-[6px] rounded-lg border border-gray-200 text-gray-700 font-semibold text-[14px] hover:bg-gray-50">
                            Cancel
                        </a>
                        <div class="flex gap-3">
                            <button type="submit" name="action" value="publish" class="px-5 py-[6px] font-semibold text-[14px] bg-[#01044e] border border-[#01044e] text-white rounded-lg hover:opacity-50">
                                <i class="bi bi-plus-circle me-2"></i>Publish
                            </button>
                            <button type="submit" name="action" value="draft" class="px-5 py-[6px] font-semibold text-[14px] border border-[#01044e] text-[#01044e] rounded-lg hover:opacity-50">
                                <i class="bi bi-save me-2"></i>Save as Draft
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-[#622733] text-white p-3 rounded-lg text-[14px] mt-4" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Note:</strong> You will be automatically set as the host of this event.
        </div>
    </div>

@endsection