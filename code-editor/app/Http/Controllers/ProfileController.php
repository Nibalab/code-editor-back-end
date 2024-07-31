<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Models\Asset; // Assuming you have an Asset model to handle asset files

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $userId = $user->id;

        $profile = Profile::firstOrCreate(
            ['user_id' => $userId],
            ['bio' => '', 'readme_content' => '']
        );

        return response()->json([
            'user_id' => $userId,
            'profile' => $profile
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        $profile = Profile::firstOrCreate(
            ['user_id' => $userId],
            ['bio' => '', 'readme_content' => '']
        );

        $profile->update([
            'bio' => $request->input('bio'),
            'readme_content' => $request->input('readme_content', $profile->readme_content)
        ]);

        return response()->json(['profile' => $profile]);
    }

    public function uploadReadme(Request $request, Profile $profile)
    {
        $request->validate([
            'readme_file' => 'required|file|mimes:md'
        ]);

        $readmeFile = $request->file('readme_file');
        $content = file_get_contents($readmeFile->getRealPath());

        $profile->readme_content = $content;
        $profile->save();

        return response()->json(['message' => 'README.md content saved successfully.']);
    }

    public function getReadme()
    {
        $user = Auth::user();
        $profile = Profile::where('user_id', $user->id)->first();

        if ($profile && $profile->readme_content) {
            return response()->json(['readme_content' => $profile->readme_content]);
        }

        return response()->json(['readme_content' => ''], 404);
    }
}