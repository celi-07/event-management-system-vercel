<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function getMyData() {
        if (Auth::check()) {
            $userId = Auth::id();
            
            $allInvitations = Invitation::all();
            $invitations = User::find($userId)
                ->invitations()
                ->get();
            $events = User::find($userId)
                ->hostedEvents()
                ->get();
            
            return view('dashboard', [
                'page' => 'Dashboard',
                'user' => User::find($userId),
                'allInvitations' => $allInvitations,
                'invitations' => $invitations,
                'events' => $events,
            ]);
        }

        return redirect()->route('auth');
    }
}