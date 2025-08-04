<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function edit()
    {   
        $user = Auth::user();
        $profile = $user->profile;
        return view('profile.edit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

        $user = Auth::user();

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only('phone', 'address')
        );

        return redirect()->route('profile.edit')->with('success', 'Profile updated!');
    }

    public function destroy(Request $request)
{
    // Example: delete the user's profile and/or the user account
    $user = Auth::user();

    // Option 1: delete only user profile
    if ($user->profile) {
        $user->profile->delete();
    }
    // Option 2: delete the user entirely (CAREFUL: logs them out!)
    // $user->delete();

    // Redirect after deletion
    return redirect('/')->with('success', 'Profile deleted!');
}
}
