<?php
namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\Request;

class CodeController extends Controller
{
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string',
            'language' => 'required|string|max:255',
        ]);

        $validatedData['user_id'] = $request->user()->id;

        $code = Code::create($validatedData);

        return response()->json($code, 201);
    }

   
    public function index(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $codes = Code::where('user_id', $validatedData['user_id'])->get();
        return response()->json($codes);
    }

    public function loggedIn(Request $request)
{
    $userId = $request->user()->id; 
    $codes = Code::where('user_id', $userId)->get(); 

    return response()->json($codes);
}


    
    public function show($id)
    {
        $code = Code::find($id);

        if (!$code) {
            return response()->json(['message' => 'Source code not found'], 404);
        }

        return response()->json($code);
    }

    
    public function update(Request $request, $id)
    {
        $code = Code::find($id);

        if (!$code) {
            return response()->json(['message' => 'Source code not found'], 404);
        }

        if ($request->user()->id !== $code->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'sometimes|required|string',
            'language' => 'sometimes|required|string|max:255',
        ]);

        $code->update($validatedData);

        return response()->json($code);
    }

    
    public function destroy(Request $request, $id)
    {
        $code = Code::find($id);

        if (!$code) {
            return response()->json(['message' => 'Source code not found'], 404);
        }

        if ($request->user()->id !== $code->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $code->delete();

        return response()->json(['message' => 'Source code deleted']);
    }
}
