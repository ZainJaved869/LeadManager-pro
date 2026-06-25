<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AISettingsController extends Controller
{
    public function index()
    {
        $aiProvider = config('ai.default_provider');
        $groqKey = config('ai.groq_key') ? '••••••••' : '';
        return view('admin.ai-settings.index', compact('aiProvider', 'groqKey'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'provider' => 'required|in:groq,openai',
            'groq_key' => 'nullable|string',
            'openai_key' => 'nullable|string',
        ]);

        // Update .env
        $env = file_get_contents(base_path('.env'));
        $env = preg_replace("/^AI_DEFAULT_PROVIDER=.*/m", "AI_DEFAULT_PROVIDER={$validated['provider']}", $env);
        if ($request->groq_key) {
            $env = preg_replace("/^GROQ_API_KEY=.*/m", "GROQ_API_KEY={$request->groq_key}", $env);
        }
        if ($request->openai_key) {
            $env = preg_replace("/^OPENAI_API_KEY=.*/m", "OPENAI_API_KEY={$request->openai_key}", $env);
        }
        file_put_contents(base_path('.env'), $env);
        Artisan::call('config:cache');

        return redirect()->route('admin.ai-settings')->with('success', 'AI settings updated.');
    }
}