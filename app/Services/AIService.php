<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('ai.groq_key');
    }

    public function generateEmail($input)
    {
        $prompt = "Write a persuasive professional sales email based on this context:\n\n" . $input . "\n\n" .
                  "The email should be clear, concise, and compelling. Include a subject line, a greeting, " .
                  "a value proposition, a call to action, and a professional closing.";

        return $this->callGroq($prompt);
    }

    public function generateFollowup($context, $previousEmail = null)
    {
        $prompt = "Write a professional follow-up email based on this context:\n\n" . $context . "\n\n";
        if ($previousEmail) {
            $prompt .= "Previous email sent:\n" . $previousEmail . "\n\n";
        }
        $prompt .= "The follow-up should be polite, remind the recipient of the previous conversation, " .
                   "add value, and gently nudge for a response.";

        return $this->callGroq($prompt);
    }

    public function generateProposal($leadDetails)
    {
        $prompt = "Create a professional sales proposal outline based on this lead details:\n\n" . $leadDetails . "\n\n" .
                  "Include: Executive Summary, Solution Overview, Pricing, Timeline, and Next Steps.";

        return $this->callGroq($prompt);
    }

    public function generateSummary($leadData)
    {
        $prompt = "Summarize this lead's information in a concise, professional summary:\n\n" . $leadData . "\n\n" .
                  "Include key details, potential challenges, opportunities, and recommended next actions.";

        return $this->callGroq($prompt);
    }

    protected function callGroq($prompt)
    {
        if (empty($this->apiKey)) {
            return 'Error: Groq API key is missing. Please add it to your .env file.';
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.1-8b-instant', // Stable model – you can also use 'llama3-70b-8192' or 'mixtral-8x7b-32768'
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a professional sales assistant.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
                'max_tokens' => 800,
            ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                if ($content) {
                    return $content;
                }
                return 'Error: Empty response from Groq.';
            }

            // Log the full error for debugging
            Log::error('Groq API Error Response: ' . $response->body());
            return 'Error generating content: ' . ($response->json('error.message') ?? 'Unknown error. Please check your API key and try again.');
        } catch (\Exception $e) {
            Log::error('Groq API Exception: ' . $e->getMessage());
            return 'Error: ' . $e->getMessage();
        }
    }
}