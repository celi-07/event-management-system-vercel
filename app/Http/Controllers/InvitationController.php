<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invitation;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function getInvitations(Request $r) {
        $userId = auth()->id() ?? User::query()->value('id');

        if (!$userId) {
            return view('invitations', [
                'page' => 'Invitations',
                'invitations' => collect(),
            ]);
        }

        $query = $r->input('q');
        
        if (!$query) {
            $invitations = User::find($userId)
                ->invitations()
                ->get();

            return view('invitations', [
                'page' => 'Invitations',
                'invitations' => $invitations,
            ]);
        } else {
            $invitations = [];
            $invitations = User::find($userId)
                ->invitations()
                ->get();
            
            $invitations = $invitations->filter(function ($invitation) use ($query) {
                return str_contains(strtolower($invitation->event->title), strtolower($query));
            });

            return view('invitations', [
                'page' => 'Invitations',
                'invitations' => $invitations,
            ]);
        }
    }

    public function registerInvitation($eventId) {
        try {
            $user = auth()->user();
            
            if (Invitation::where('event_id', $eventId)->where('invitee_id', $user->id)->exists()) {
                return back()->with('error', 'You have already registered for this event.');
            }
            
            Invitation::create([
                'event_id' => $eventId,
                'invitee_id' => $user->id,
                'status' => 'Pending',
                'sent_at' => NULL,
                'responded_at' => now(),
            ]);

            return back()->with('success', "Successfully registered for the event!");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to register for the event. Please try again.');
        }
    }

    public function respondInvitation(Request $request, Invitation $invitation)
    {
        $request->validate([
            'status' => 'required|in:Accepted,Declined',
        ]);

        $user = auth()->user();

        if (!$user || $invitation->invitee_id !== $user->id) {
            return back()->with('error', 'Not authorized to update this invitation.');
        }

        if ($invitation->status !== 'Pending') {
            return back()->with('error', 'Invitation already responded.');
        }

        $invitation->update([
            'status' => $request->input('status'),
            'responded_at' => now(),
        ]);

        return back()->with('success', 'Invitation updated to ' . strtolower($request->input('status')) . '.');
    }
}
