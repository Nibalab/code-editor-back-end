<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function show()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Access the user ID
        $userId = $user->id;

        // Find or create a profile for the user
        $profile = Profile::firstOrCreate(
            ['user_id' => $userId], // Conditions to find or create
            ['bio' => ''] // Default values if creating
        );

        // Return user profile data
        return response()->json(['user_id' => $userId, 'profile' => $profile]);
    }

    public function update(Request $request)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Access the user ID
        $userId = $user->id;

        // Find or create a profile for the user
        $profile = Profile::firstOrCreate(
            ['user_id' => $userId], // Conditions to find or create
            ['bio' => ''] // Default values if creating
        );

        // Update user profile with the provided bio
        $profile->update(['bio' => $request->input('bio')]);

        // Return success response
        return response()->json(['message' => 'Profile updated successfully']);
    }
}