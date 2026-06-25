<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'timezone' => 'required|string',
            'currency' => 'required|string|max:3',
            'maintenance_mode' => 'nullable|boolean',
        ]);

        // Update .env file (simplified – in production use a package like laravel-env)
        $this->updateEnvFile($validated);

        return redirect()->route('admin.settings')->with('success', 'Settings updated.');
    }

    protected function updateEnvFile($data)
    {
        // For demonstration – you should use a proper package for .env management
        $env = file_get_contents(base_path('.env'));
        foreach ($data as $key => $value) {
            $keyUpper = strtoupper($key);
            if ($key === 'app_name') $keyUpper = 'APP_NAME';
            if ($key === 'app_url') $keyUpper = 'APP_URL';
            if ($key === 'maintenance_mode') $keyUpper = 'APP_MAINTENANCE';

            $pattern = "/^{$keyUpper}=.*/m";
            $replacement = "{$keyUpper}={$value}";
            $env = preg_replace($pattern, $replacement, $env);
        }
        file_put_contents(base_path('.env'), $env);
        Artisan::call('config:cache');
    }
}