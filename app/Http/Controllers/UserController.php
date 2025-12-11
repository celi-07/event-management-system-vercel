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
        
        if ($user->profile_image && file_exists(public_path($user->profile_image))) {
            unlink(public_path($user->profile_image));
        }
        
        $image = $request->file('profile_image');
        $imageName = time() . '_' . $user->id . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/profile_images'), $imageName);
        
        $user->profile_image = 'uploads/profile_images/' . $imageName;
        $user->save();
        
        return back()->with('success', 'Profile Image updated successfully!');
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
