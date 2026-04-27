<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use Illuminate\Http\Request;

class ImageAIController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function __invoke(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
            'image'  => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // AMAN: Langsung ambil biner gambarnya tanpa pusing cari path-nya
        $imageRawData = $request->file('image')->get();

        // Kirim binernya ke service
        $response = $this->geminiService->analyzeImage(
            $request->prompt,
            $imageRawData
        );

        return response()->json([
            'status' => 'success',
            'data' => $response
        ]);
    }
}
