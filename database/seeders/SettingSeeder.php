<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $params = [
            [
                'name' => 'app_url',
                'value' => 'https://tyuiu.kimko.org/'
            ]
        ];

        foreach ($params as $param) {
            if(!Setting::where('name', '=', $param['name'])->first()) {
                $setting = new Setting([
                    'name' => $param['name'],
                    'value' => $param['value']
                ]);

                $setting->save();
            }
        }
    }
}
