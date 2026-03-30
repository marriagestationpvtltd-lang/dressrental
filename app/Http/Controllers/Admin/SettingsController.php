<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $grouped = Setting::groupedAll();

        return view('admin.settings.index', compact('grouped'));
    }

    public function update(Request $request): RedirectResponse
    {
        $input = $request->except(['_token', '_method']);

        // Retrieve all setting keys to know which boolean settings exist.
        $allGrouped = Setting::groupedAll();
        $booleanKeys = [];

        foreach ($allGrouped as $group => $settings) {
            foreach ($settings as $setting) {
                if ($setting['type'] === 'boolean') {
                    $booleanKeys[] = $setting['key'];
                }
            }
        }

        // Boolean checkboxes are absent from POST when unchecked — default them to 0.
        foreach ($booleanKeys as $key) {
            if (! array_key_exists($key, $input)) {
                $input[$key] = '0';
            }
        }

        foreach ($input as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings saved successfully.');
    }
}
