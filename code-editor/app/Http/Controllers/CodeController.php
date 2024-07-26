<?php
namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\Request;

class CodeController extends Controller
{
    public function createCode(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string',
            'language' => 'required|string|max:255',
        ]);

        $code = Code::create($validatedData);

        return response()->json($code, 201);
    }

    public function getAllCodes()
    {
        $codes = Code::all();
        return response()->json($codes);
    }

    public function getCode($id)
    {
        $code = Code::find($id);

        if (!$code) {
            return response()->json(['message' => 'Source code not found'], 404);
        }

        return response()->json($code);
    }

    public function updateCode(Request $request, $id)
    {
        $code = Code::find($id);

        if (!$code) {
            return response()->json(['message' => 'Source code not found'], 404);
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

    public function deleteCode($id)
    {
        $code = Code::find($id);

        if (!$code) {
            return response()->json(['message' => 'Source code not found'], 404);
        }

        $code->delete();

        return response()->json(['message' => 'Source code deleted']);
    }
}
