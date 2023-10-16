<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DepartmentType;

class DepartmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'title' => 'Корпус'
            ],
            [
                'title' => 'Отборочная комиссия'
            ],
            [
                'title' => 'Поликлиника'
            ],
            [
                'title' => 'Институт'
            ],
            [
                'title' => 'Лаборатория'
            ],
            [
                'title' => 'Приемная комиссия'
            ],
            [
                'title' => 'Комплекс'
            ],
            [
                'title' => 'ОУ'
            ],
            [
                'title' => 'Факультет'
            ],
            [
                'title' => 'Комитет'
            ],
            [
                'title' => 'Сектор'
            ],
            [
                'title' => 'Пресс-служба'
            ],
            [
                'title' => 'Музей'
            ],
            [
                'title' => 'Направление'
            ],
            [
                'title' => 'НИИ'
            ],
            [
                'title' => 'Предметно-цикловая комиссия'
            ],
            [
                'title' => 'Учебная мастерская'
            ],
            [
                'title' => 'Училище'
            ],
            [
                'title' => 'Ректорат'
            ],
            [
                'title' => 'Кафедра'
            ],
            [
                'title' => 'Отделение'
            ],
            [
                'title' => 'Библиотека'
            ],
            [
                'title' => 'Центр'
            ],
            [
                'title' => 'Отдел'
            ],
            [
                'title' => 'Управление'
            ],
            [
                'title' => 'Бюро'
            ],
            [
                'title' => 'Представительство'
            ],
            [
                'title' => 'Колледж'
            ],
            [
                'title' => 'Служба'
            ],
            [
                'title' => 'Департамент'
            ],
            [
                'title' => 'Филиал'
            ],
            [
                'title' => 'Техникум'
            ],
            [
                'title' => 'Нестационарный филиал'
            ],
            [
                'title' => 'Общежитие'
            ],
            [
                'title' => 'Лицей'
            ]
        ];

        foreach($types as $type) {
            if(!DepartmentType::where('title', '=', $type)->first()) {
                $departmentType = new DepartmentType([
                    'title' => $type['title']
                ]);

                $departmentType->save();
            }
        }
    }
}
