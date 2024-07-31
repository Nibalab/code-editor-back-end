<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Profile;
use App\Models\Code;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $query = $request->input('query');
        $type = $request->input('type');

        $user = User::where($type, $query)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $profile = Profile::where('user_id', $user->id)->first();
        $codesCount = Code::where('user_id', $user->id)->count();

        return response()->json([
            'user_id' => $user->id,
            'name' => $user->name, 
            'email' => $user->email,
            'bio' => $profile->bio,
            'codesCount' => $codesCount
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
    public function getProfile()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $profile = Profile::where('user_id', $user->id)->first();

    if (!$profile) {
        return response()->json(['message' => 'Profile not found'], 404);
    }

    $codesCount = Code::where('user_id', $user->id)->count();

    return response()->json([
        'user_id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'bio' => $profile->bio,
        'codesCount' => $codesCount,
        'readme_content' => $profile->readme_content,
    ]);
}
}