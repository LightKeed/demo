<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Slider;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!Slider::where('name', '=', 'Главный')->first()) {
            $slider = new Slider([
                'name' => 'Главный',
                'description' => 'Слайдер на главной странице',
                'enabled' => 1,
                'can_removed' => 0
            ]);

            $slider->save();
        }
    }
}
