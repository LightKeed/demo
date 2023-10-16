<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!User::where('email', '=', 'admin@tyuiu.ru')->first()) {
            $user = new User([
                'surname' => 'Админов',
                'name' => 'Админ',
                'patronymic' => 'Админович',
                'email' => 'admin@tyuiu.ru',
                'email_verified_at' => 1,
                'password'=> Hash::make(Str::uuid())
            ]);

            $user->save();
        }
    }
}
