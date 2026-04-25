<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use Illuminate\Http\Request;

class AIController extends Controller
{
    // Kita simpan service di properti class
    protected $geminiService;

    // Recursive Learning: Dependency Injection
    // Kita menyuntikkan GeminiService langsung ke constructor
    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    } 

    public function __invoke(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
            'history' => 'nullable|array',
        ]);

        $prompt = $request->input('prompt');

        $response = $this->geminiService->chatWithHistory(
            $request->prompt,
            $request->history ?? []
        );

        return response()->json([
            'message' => 'success',
            'data' => $response,
        ]);
    }
}
