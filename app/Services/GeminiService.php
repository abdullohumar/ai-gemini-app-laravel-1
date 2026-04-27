<?php

namespace App\Services;

use Exception;
use Gemini\Data\Blob;
use Gemini\Data\Content;
use Gemini\Enums\MimeType;
use Gemini\Enums\Role;
use Gemini\Laravel\Facades\Gemini;

class GeminiService
{
    /**
     * Mengirim prompt teks ke gemini
     */

    // public function generateText(string $prompt): string
    // {
    //     try {
    //         // Kita definisikan instruksi sistem (Persona AI)
    //         $systemText = "Anda adalah Senior Backend Developer yang ahli dalam Laravel.
    //                               Jawablah pertanyaan dengan teknis, singkat, dan gunakan analogi kopi.";

    //         /**
    //          * Recursive Learning: 
    //          * Kita tetap menggunakan model 'gemini-2.5-flash' yang tadi sudah terbukti jalan.
    //          * Kita tambahkan method withSystemInstruction.
    //          */
    //         $systemInstruction = Content::parse($systemText);
    //         $result = Gemini::generativeModel('gemini-2.5-flash')->withSystemInstruction($systemInstruction)->generateContent($prompt);

    //         return $result->text();
    //     } catch (Exception $e) {
    //         // Error handling dasar untuk level produksi
    //         return "Error:" . $e->getMessage();
    //     }
    // }

    public function chatWithHistory(string $prompt, array $history = []): string
    {
        try {
            // 1. Mapping raw array history menjadi objek Content
            $formattedHistory = collect($history)->map(function ($item) {
                return Content::parse(
                    $item['parts'],
                    Role::tryFrom($item['role']) ?? Role::USER
                );
            })->toArray();

            $systemText = "Anda adalah asisten teknis Laravel yang ramah.";

            // 2. Gunakan history yang sudah diformat
            $chat = Gemini::generativeModel('gemini-2.5-flash')
                ->withSystemInstruction(Content::parse($systemText))
                ->startChat(history: $formattedHistory);

            $response = $chat->sendMessage($prompt);

            return $response->text();
        } catch (Exception $e) {
            return "Error Detail: " . $e->getMessage();
        }
    }

    // Ubah parameter dari $imagePath menjadi $imageRawData
    public function analyzeImage(string $prompt, $imageRawData): string
    {
        try {
            // 1. Langsung encode biner yang dikirim dari controller
            $imageData = base64_encode($imageRawData);

            $imageBlob = new Blob(
                mimeType: MimeType::IMAGE_JPEG,
                data: $imageData
            );

            $result = Gemini::generativeModel('gemini-2.5-flash')
                ->generateContent([$prompt, $imageBlob]);

            return $result->text();
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
