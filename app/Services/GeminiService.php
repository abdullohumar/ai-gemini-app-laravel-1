<?php

namespace App\Services;

use Exception;
use Gemini\Laravel\Facades\Gemini;

class GeminiService
{
    /**
     * Mengirim prompt teks ke gemini
     */

    public function generateText(string $prompt): string
    {
        try {
            $result = Gemini::geminiPro()->generateContent($prompt);

            return $result->text();
        } catch (Exception $e) {
            // Error handling dasar untuk level produksi
            return "Error:" . $e->getMessage();
        }
    }
}