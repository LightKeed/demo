<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Admin\Setting\SettingCreateRequest;
use App\Http\Requests\Admin\Setting\SettingUpdateRequest;
use App\Models\Setting;

final class SettingService extends BaseService
{
    public function index()
    {
        $sections = json_decode(config('settings.settings_sections'), true) ?? [];
        $settings = Setting::select('id', 'name', 'value')->filter(Request::only('name'))->get();

        return Inertia::render('Admin/Setting/Index', [
            'filters' => Request::all('name'),
            'sections' => $sections,
            'settings' => $settings,
        ]);
    }

    public function show(string $path)
    {
        $sections = json_decode(config('settings.settings_sections'), true) ?? abort(404);
        $findSection = null;

        foreach($sections as $key => $section) {
            if($section['section'] === ucfirst($path)) {
                $findSection = $section;
                break;
            }
        }

        if(!$findSection) {
            abort(404);
        }

        $settings = Setting::select('id', 'name', 'value')
            ->whereIn('name', preg_split("/[\s,]+/", $findSection['settings'] ?? ''))
            ->get();

        return Inertia::render('Admin/Setting/Show', [
            'sections' => $sections,
            'section' => $findSection,
            'settings' => $settings
        ]);
    }

    public function store(SettingCreateRequest $request)
    {
        $address = new Address($request->validated());

        $address->save();

        return to_route('Admin.AddressIndex')->with([
            'success' => 'Адрес успешно создан.'
        ]);
    }

    public function update(SettingUpdateRequest $request)
    {
        foreach ($request->settings as $key => $value) {
            if($setting = $this->getSettingByName($key)) {
                $setting->update(['value' => $value]);
            } else {
                $setting = new Setting([
                    'name' => $key,
                    'value' => $value
                ]);

                $setting->save();
            }
        }

        return redirect()->back()->with([
            'success' => 'Настройки успешно обновлены.'
        ]);
    }

    public function remove(int $id)
    {
        try {
            $address = $this->getAddressByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Адрес не найден.']);
        }

        $address->delete();

        return to_route('Admin.AddressIndex')->with([
            'success' => 'Адрес успешно удален.'
        ]);
    }

    private function getAddressByID(int $id)
    {
        return Address::where('id', '=', $id)
            ->firstOrFail();
    }

    private function getSettingByName(string $name)
    {
        return Setting::where('name', '=', $name)->first();
    }
}