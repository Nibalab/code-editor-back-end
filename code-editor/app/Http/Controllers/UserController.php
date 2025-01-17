<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function getUserBy(Request $request)
    {
        
        $request->validate([
            'query' => 'required|string',
            'type' => 'required|string|in:email,username',
        ]);

        
        $query = $request->input('query');
        $type = $request->input('type');


        if ($type === 'email') {
            $user = User::where('email', $query)->first();
        } else {
            $user = User::where('username', $query)->first();
        }

    
        if ($user) {
            return response()->json([
                "user" => $user
            ], 200);
        } else {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }
    }
    

    public function getAllUsers()
    {
        $users = User::all();
        return response()->json([
            "users" => $users
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function getUser($id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }

        return response()->json([
            "user" => $user
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createUser(Request $req)
    {
        $req->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'is_admin' => 'nullable|boolean',  // Validate is_admin
        ]);

        $user = new User;
        $user->name = $req->name;
        $user->email = $req->email;
        $user->password = Hash::make($req->password);
        $user->is_admin = $req->input('is_admin', false);  // Default to false if not provided
        $user->save();

        return response()->json([
            "user" => $user,
            "message" => 'User created successfully'
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateUser(Request $req, $id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }

        $req->validate([
            'name' => 'sometimes|required',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'sometimes|nullable|min:6',
            'is_admin' => 'nullable|boolean',  // Validate is_admin
        ]);

        $user->name = $req->input('name', $user->name);
        $user->email = $req->input('email', $user->email);

        if ($req->has('password')) {
            $user->password = Hash::make($req->password);
        }

        if ($req->has('is_admin')) {
            $user->is_admin = $req->input('is_admin');
        }

        $user->save();

        return response()->json([
            "user" => $user,
            "message" => 'User updated successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteUser($id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }

        $user->delete();

        return response()->json([
            "message" => "User deleted successfully"
        ], 204);
    }

    /**
     * Search for users by name.
     */
    public function searchUsers(Request $request)
    {
        $query = $request->input('query');
        if (!$query) {
            return response()->json(['message' => 'Query parameter is required'], 400);
        }

        $users = User::where('name', 'LIKE', "%{$query}%")->get();

        return response()->json($users);
    }
}
?>
