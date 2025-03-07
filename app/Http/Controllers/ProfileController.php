<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        return view('auth.profile');
    }

    public function update(ProfileUpdateRequest $request)
    {
        // Task: fill in the code here to update name and email
        // Also, update the password if it is set
        $request->user()->fill($request->validated());

        $request->user()->save();

        if($request->filled('password')) {
            $request->validate([
                'password' => Password::defaults()->letters(),
                'password_confirmation' => 'same:password'
            ]);
            User::whereId(auth()->user()->id)->update([
                'password' => Hash::make($request->password)
            ]);
        }
        

        return redirect()->route('profile.show')->with('success', 'Profile updated.');
    }
}
