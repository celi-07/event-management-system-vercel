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

  <section class="xl:col-span-2">
    <div class="rounded-2xl border border-gray-200 bg-white">
      <div class="flex items-center justify-between p-4 border-b border-gray-100">
        <h2 class="font-semibold">My Events</h2>
        @if(auth()->user()?->is_organizer)
          <div class="flex items-center gap-2">
            <a href="{{ url('/create/events') }}" class="text-sm rounded-lg border px-3 py-1.5 hover:bg-gray-50">Create</a>
          </div>
        @endif
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-200 text-[#000]">
            <tr>
              <th class="px-4 py-[8px] text-center font-semibold">Title</th>
              <th class="px-4 py-[8px] text-center font-semibold">Date</th>
              <th class="px-4 py-[8px] text-center font-semibold">Visitors</th>
              <th class="px-4 py-[8px] text-center font-semibold">Status</th>
              <th class="px-4 py-[8px] text-center font-semibold">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @if ($events->count() == 0)
              <tr>
                <td colspan="5" class="px-4 py-3">
                  <x-empty-data class="h-[241px] flex flex-col items-center justify-center" />
                </td>
              </tr>
            @else
              @foreach ($events as $e)
                <tr>
                  <td class="px-4 py-3 text-center text-[14px]">{{ $e['title'] }}</td>
                  <td class="px-4 py-3 text-center text-[14px] font-light">{{ \Illuminate\Support\Carbon::parse($e['date'])->format('D, M j · H:i') }}</td>
                  <td class="px-4 py-3 text-center text-[14px] font-light">{{ $e['visitor_count'] }}</td>
                  <td class="px-4 py-3 text-center">
                    <span @class([
                      'text-[12px] inline-block w-[80px] py-1 rounded-full text-center font-semibold',
                      'bg-[#01044e] text-[#fff]' => $e['status']==='Published',
                      'bg-indigo-50 text-text-[#01044e]' => $e['status']==='Draft',
                    ])>{{ $e['status'] }}</span>
                  </td>
                  <td class="px-4 py-3 text-center">
                    <a href="{{ url('/events/' . $e['id'] . '/edit') }}" class="text-[#01044e] text-[12px] font-semibold hover:underline">Edit</a>
                    <span class="mx-2 text-gray-300">|</span>
                    <a href="{{ url('/events/' . $e['id']) }}" class="text-[#622733] text-[12px] font-semibold hover:underline">View</a>
                  </td>
                </tr>
              @endforeach
            @endif
          </tbody>
        </table>
      </div>
  </section>

@endsection