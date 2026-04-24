<?php

namespace App\Services;

use Exception;
use Gemini\Data\Content;
use Gemini\Laravel\Facades\Gemini;

class GeminiService
{
    /**
     * Mengirim prompt teks ke gemini
     */

    public function generateText(string $prompt): string
    {
        try {
            // Kita definisikan instruksi sistem (Persona AI)
            $systemText = "Anda adalah Senior Backend Developer yang ahli dalam Laravel.
                                  Jawablah pertanyaan dengan teknis, singkat, dan gunakan analogi kopi.";

            /**
             * Recursive Learning: 
             * Kita tetap menggunakan model 'gemini-2.5-flash' yang tadi sudah terbukti jalan.
             * Kita tambahkan method withSystemInstruction.
             */
            $systemInstruction = Content::parse($systemText);
            $result = Gemini::generativeModel('gemini-2.5-flash')->withSystemInstruction($systemInstruction)->generateContent($prompt);

            return $result->text();
        } catch (Exception $e) {
            // Error handling dasar untuk level produksi
            return "Error:" . $e->getMessage();
        }
    }
}