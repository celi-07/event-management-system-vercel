<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function updateProfileImage() {
        $request = request();
        
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $user = auth()->user();
        $image = $request->file('profile_image');
        
        try {
            if ($user->imagekit_file_id) {
                $client = new \GuzzleHttp\Client();
                try {
                    $client->request('DELETE', 'https://api.imagekit.io/v1/files/' . $user->imagekit_file_id, [
                        'auth' => [env('IMAGEKIT_PRIVATE_KEY'), '']
                    ]);
                } catch (\Exception $e) {
                    // Continue even if delete fails
                }
            }
            
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
                        'contents' => 'profile_' . $user->id . '_' . time() . '.' . $image->getClientOriginalExtension()
                    ],
                    [
                        'name' => 'folder',
                        'contents' => '/profile_images'
                    ]
                ]
            ]);
            
            $result = json_decode($response->getBody(), true);
            
            $user->profile_image = $result['url'];
            $user->imagekit_file_id = $result['fileId'];
            $user->save();
            
            return back()->with('success', 'Profile Image updated successfully!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error uploading image: ' . $e->getMessage());
        }
    }

    public function updateProfileInfo() {
        $request = request();
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
        ]);
        
        $user = auth()->user();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();
        
        return back()->with('success', 'Personal Information updated successfully!');
    }

    public function updatePassword() {
        $request = request();
        
        $data = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);
        
        $user = auth()->user();
        
        if (!password_verify($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }
        
        $user->password = Hash::make($data['password']);
        $user->save();
        
        return back()->with('success', 'Password updated successfully!');
    }
}
