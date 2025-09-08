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
    $user = Auth::user();

    if ($user->profile) {
        $user->profile->delete();
    }
    
    return redirect('/')->with('success', 'Profile deleted!');
}
}
