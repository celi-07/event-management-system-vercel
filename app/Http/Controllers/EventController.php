<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Invitation;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function discover(Request $r) {
        $query = $r->input('q');

        if (!$query) {
            return view('discover', [
                'page' => 'Discover',
                'events' => Event::all()->where('status', 'Published'),
            ]);
        } else {
            $events = [];
            $events = Event::where('title', 'like', '%' . $query . '%')
                ->get();
            
            return view('discover', [
                'page' => 'Discover',
                'events' => $events,
            ]);
        }
    }

    public function getCreate() {
        return view('create-event', [
            'page' => 'Create Event',
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'location' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $status = $request->input('action') === 'draft' ? 'Draft' : 'Published';
            $imagePath = null;
            $imagekitFileId = null;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                try {
                    $client = new \GuzzleHttp\Client();
                    $response = $client->request('POST', 'https://upload.imagekit.io/api/v1/files/upload', [
                        'auth' => [env('IMAGEKIT_PRIVATE_KEY'), ''],
                        'multipart' => [
                            [
                                'name' => 'file',
                                'contents' => fopen($image->getRealPath(), 'r'),
                                'filename' => $image->getClientOriginalName()
                            ],
                            [
                                'name' => 'fileName',
                                'contents' => 'event_' . time() . '_' . auth()->id() . '.' . $image->getClientOriginalExtension()
                            ],
                            [
                                'name' => 'folder',
                                'contents' => '/event_images'
                            ]
                        ]
                    ]);
                    
                    $result = json_decode($response->getBody(), true);
                    $imagePath = $result['url'];
                    $imagekitFileId = $result['fileId'];
                } catch (\Exception $e) {
                    return back()->with('error', 'Error uploading image: ' . $e->getMessage())->withInput();
                }
            }

            $event = Event::create([
                'title' => $data['title'],
                'date' => $data['date'],
                'location' => $data['location'],
                'description' => $data['description'],
                'image' => $imagePath,
                'imagekit_file_id' => $imagekitFileId,
                'host_id' => auth()->id(),
                'status' => $status,
            ]);

            if ($status === 'Published') {
                return back()->with('success', 'Event created successfully!');
            } else {
                return back()->with('success', 'Event saved as draft successfully!');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create event. Please try again.')
                ->withInput();
        }
    }

    public function getEdit($id) {
        $event = Event::findOrFail($id);

        return view('edit-event', [
            'page' => 'Edit Event',
            'event' => $event,
        ]);
    }

    public function update(Request $request, $id) {
        $event = Event::findOrFail($id);

        if ($event->status === 'Published') {
            return back()->with('error', 'Published events cannot be edited.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'location' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $imagePath = $event->image;
            $imagekitFileId = $event->imagekit_file_id;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                if ($event->imagekit_file_id) {
                    $client = new \GuzzleHttp\Client();
                    try {
                        $client->request('DELETE', 'https://api.imagekit.io/v1/files/' . $event->imagekit_file_id, [
                            'auth' => [env('IMAGEKIT_PRIVATE_KEY'), '']
                        ]);
                    } catch (\Exception $e) {
                        // Continue even if delete fails
                    }
                }
                
                try {
                    $client = new \GuzzleHttp\Client();
                    $response = $client->request('POST', 'https://upload.imagekit.io/api/v1/files/upload', [
                        'auth' => [env('IMAGEKIT_PRIVATE_KEY'), ''],
                        'multipart' => [
                            [
                                'name' => 'file',
                                'contents' => fopen($image->getRealPath(), 'r'),
                                'filename' => $image->getClientOriginalName()
                            ],
                            [
                                'name' => 'fileName',
                                'contents' => 'event_' . time() . '_' . auth()->id() . '.' . $image->getClientOriginalExtension()
                            ],
                            [
                                'name' => 'folder',
                                'contents' => '/event_images'
                            ]
                        ]
                    ]);
                    
                    $result = json_decode($response->getBody(), true);
                    $imagePath = $result['url'];
                    $imagekitFileId = $result['fileId'];
                } catch (\Exception $e) {
                    return back()->with('error', 'Error uploading image: ' . $e->getMessage())->withInput();
                }
            }

            $newStatus = $event->status;
            if ($event->status === 'Draft' && $request->boolean('publish_now')) {
                $newStatus = 'Published';
            }

            $event->update([
                'title' => $data['title'],
                'date' => $data['date'],
                'location' => $data['location'],
                'description' => $data['description'],
                'image' => $imagePath,
                'imagekit_file_id' => $imagekitFileId,
                'status' => $newStatus,
            ]);

            return back()->with('success', $newStatus === 'Published' ? 'Event updated and published successfully!' : 'Event updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update event. Please try again.')
                ->withInput();
        }
    }

    public function destroy($id) {
        $event = Event::findOrFail($id);

        try {
            // Delete image from ImageKit if exists
            if ($event->imagekit_file_id) {
                $client = new \GuzzleHttp\Client();
                try {
                    $client->request('DELETE', 'https://api.imagekit.io/v1/files/' . $event->imagekit_file_id, [
                        'auth' => [env('IMAGEKIT_PRIVATE_KEY'), '']
                    ]);
                } catch (\Exception $e) {
                    // Continue even if delete fails
                }
            }
            
            $event->delete();
            return redirect()->route('my.events')->with('success', 'Event deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete event. Please try again.');
        }
    }

    public function getDetail($id) {
        $event = Event::find($id);
        $isHost = auth()->check() && auth()->id() === $event->host_id;
        $isRegistered = auth()->check() && Invitation::where('event_id', $id)->where('invitee_id', auth()->id())->exists();
        
        return view('detail-event', [
            'page' => 'Event Detail',
            'event' => $event,
            'isHost' => $isHost,
            'isRegistered' => $isRegistered,
        ]);
    }

    public function getMyEvents(Request $r) {
        $userId = auth()->id();

        $query = $r->input('q');
        
        if (!$query) {
            $events = User::find($userId)
                ->hostedEvents()
                ->get();

            return view('my-events', [
                'page' => 'My Events',
                'events' => $events,
            ]);
        } else {
            $events = [];
            $events = User::find($userId)
                ->hostedEvents()
                ->where('title', 'like', '%' . $query . '%')
                ->get();

            return view('my-events', [
                'page' => 'My Events',
                'events' => $events,
            ]);
        }
    }

    public function sendInvite(Request $request, $eventId)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $event = Event::find($eventId);
        $user = auth()->user();

        if ($event->host_id !== $user->id) {
            return back()->with('error', 'Only the event host can invite users.');
        }

        $invitedUser = User::where('email', $request->input('email'))->first();

        if (!$invitedUser) {
            return back()->with('error', 'User with this email not found.');
        }

        if (Invitation::where('event_id', $eventId)->where('invitee_id', $invitedUser->id)->exists()) {
            return back()->with('error', 'User already invited to this event.');
        }

        Invitation::create([
            'event_id' => $eventId,
            'invitee_id' => $invitedUser->id,
            'status' => 'Pending',
            'sent_at' => now(),
        ]);

        return back()->with('success', 'Invitation sent to ' . $invitedUser->name . '!');
    }
}
