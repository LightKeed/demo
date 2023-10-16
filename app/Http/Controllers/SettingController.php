<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Cache\Factory;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return view('backend.modules.setting.index', compact('settings'))->withTitle('List of Settings');
    }

    public function edit(Setting $setting)
    {
        return view('backend.modules.setting.edit', compact('setting'))->withTitle('Edit a Setting');
    }

    public function update(Request $request, Factory $cache, Setting $setting)
    {
        if ($request->isMethod('put')) {
            $rules = [
                'value' => 'required'
            ];
            $messages = [
                'value.required' => 'Value of Setting is required.'
            ];
            $this->validate($request, $rules, $messages);
            $setting->value = $request->value;
            $setting->save();
            // When the settings have been updated, clear the cache for the key 'settings'
            $cache->forget('settings');
            return redirect()->route('admin.settings.index')->with('success', 'Item has been updated successfully.');
        }
    }
}
