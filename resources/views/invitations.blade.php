@extends('layouts.app')

@section('content')
<section class="xl:col-span-1">
      <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="flex items-center justify-between p-4 border-b border-gray-100">
          <h2 class="font-bold text-[16px]">Upcoming Invitations</h2>
        </div>
        <ul class="divide-y divide-gray-100">
          @if ($invitations->count() == 0)
            <x-empty-data class="h-[300px] flex flex-col items-center justify-center" />
          @else
            @foreach ($invitations as $inv)
              <li class="py-3 px-4 flex items-center justify-between {{ $inv['status']==='Pending' ? 'cursor-pointer hover:bg-gray-50 transition-colors' : '' }}"
                  @if($inv['status']==='Pending')
                      data-inv-id="{{ $inv->id }}"
                      data-event-title="{{ $inv->event->title }}"
                      onclick="openInvitationModal(this)"
                  @endif>
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

    <div id="invitation-modal" class="hidden fixed inset-0 z-40 items-center justify-center bg-black/40">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 p-6 relative">
            <h3 class="text-xl font-bold mb-2" id="invitation-modal-title">Respond to Invitation</h3>
            <p class="text-[14px] font-semibold text-gray-600 mb-4" id="invitation-modal-event"></p>
            <div class="flex justify-between items-center">
                <button type="button" onclick="closeInvitationModal()" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 font-semibold text-[14px] hover:bg-gray-50">Cancel</button>
                <div class="flex gap-3 justify-end">
                  <button type="button" onclick="submitInvitationResponse('Declined')" class="px-4 py-2 rounded-lg border border-[#622733] text-[#622733] font-semibold text-[14px] hover:bg-[#622733] hover:text-white">Decline</button>
                  <button type="button" onclick="submitInvitationResponse('Accepted')" class="px-4 py-2 rounded-lg bg-[#01044e] text-white font-semibold text-[14px] hover:opacity-80">Accept</button>
                </div>
            </div>
            <button type="button" onclick="closeInvitationModal()" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600">✕</button>
        </div>
        <form id="invitation-response-form" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="status" id="invitation-status-input" value="">
        </form>
    </div>

    <script>
        const modal = document.getElementById('invitation-modal');
        const responseForm = document.getElementById('invitation-response-form');
        const statusInput = document.getElementById('invitation-status-input');
        const modalEvent = document.getElementById('invitation-modal-event');
        const respondBaseUrl = "{{ url('/invitations') }}";

        function openInvitationModal(el) {
            const invitationId = el.getAttribute('data-inv-id');
            const eventTitle = el.getAttribute('data-event-title');

            modalEvent.textContent = 'Event Title: ' + eventTitle;
            responseForm.action = `${respondBaseUrl}/${invitationId}/respond`;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeInvitationModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function submitInvitationResponse(status) {
            statusInput.value = status;
            responseForm.submit();
        }
    </script>
@endsection