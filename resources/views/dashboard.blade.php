@extends('layouts.app')

@section('title','Dashboard')

@section('content')
  <div class="mb-4 flex items-center justify-between">
    <div>
      <h1 class="text-3xl font-bold">Dashboard</h1>
      <p class="text-[14px] text-gray-600">Track events, invitations, and attendance. If these numbers are flat, your growth is flat—fix it fast.</p>
    </div>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-4">
    <x-stat-card label="My Events" :value="count($events)"/>
    <x-stat-card label="Invitations Sent" :value="count($invitations)"/>
    <x-stat-card label="Pending Invites" :value="count($invitations->where('status', 'Pending'))"/>
    <x-stat-card label="Total Attendees" :value="$invitations->whereIn('event_id', $events->pluck('id'))->where('status','Accepted')->count()"/>
  </div>

  <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <section class="xl:col-span-1">
      <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="flex items-end justify-between p-4 border-b border-gray-100">
          <h2 class="font-bold text-[16px]">Your Invitations</h2>
          <a href="{{ url('/invitations') }}" class="text-[12px] font-semibold text-[#01044e] hover:underline">View all</a>
        </div>
        <ul class="divide-y divide-gray-100">
          @if ($invitations->count() == 0)
            <x-empty-data class="h-[300px] flex flex-col items-center justify-center" />
          @else
            @foreach ($invitations->take(8) as $inv)
              <li class="py-3 px-4 flex items-center justify-between">
                <div>
                  <div class="font-regular text-[14px]">{{ $inv->event->title }}</div>
                  <div class="text-[12px] text-gray-600 font-light">
                    {{ \Illuminate\Support\Carbon::parse($inv['date'])->format('D, M j · H:i') }} · Host: {{ $inv->event->host->name }}
                  </div>
                </div>
                <span @class([
                    'text-[12px] text-center font-semibold inline-block w-[80px] py-1 rounded-full',
                    'bg-[#01044e] text-white' => $inv['status']==='Accepted',
                    'bg-indigo-50 text-[#01044e]' => $inv['status']==='Pending',
                    'bg-[#622733] text-white' => $inv['status']==='Declined',
                ])>{{ $inv['status'] }}</span>
              </li>
            @endforeach
          @endif
        </ul>
      </div>
    </section>

    <section class="xl:col-span-2">
      <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
          <h2 class="font-bold text-[16px]">My Events</h2>
          @if(auth()->user()?->is_organizer)
            <div class="flex items-center gap-2">
              <a href="{{ url('/create/events') }}" class="text-[12px] rounded-xl bg-[#01044e] font-semibold text-white border px-3 py-1.5 hover:bg-[#622733]">Create</a>
              <a href="{{ url('/my-events') }}" class="text-[12px] text-[#01044e] hover:underline">Manage</a>
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
                @foreach ($events->take(9) as $e)
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
  </div>
@endsection
