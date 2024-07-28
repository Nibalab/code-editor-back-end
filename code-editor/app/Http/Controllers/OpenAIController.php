<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class OpenAIController extends Controller
{
    public function getSuggestions(Request $request)
    {
        $apiKey = env('API_KEY');
        $prompt = $request->input('prompt');
        $language = $request->input('language');

        if (!$apiKey || !$prompt || !$language) {
            Log::error('Missing required parameters or API key');
            return response()->json(['error' => 'Bad Request'], 400);
        }

        try {
            $client = new Client();
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'user', 'content' => "Provide code completion for the following $language code snippet:\n$prompt"]
                    ],
                    'max_tokens' => 100,
                    'temperature' => 0.7,
                ],
            ]);

            return response()->json(json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            Log::error('OpenAI API request failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
