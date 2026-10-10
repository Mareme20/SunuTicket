<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\Phone;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /** Crée (ou met à jour) le compte administrateur à partir du fichier .env. */
    public function run(): void
    {
        $admin = config('sunuticket.admin');

        User::updateOrCreate(
            ['phone' => Phone::normalize($admin['phone'])],
            [
                'name' => $admin['name'],
                'email' => $admin['email'],
                'password' => $admin['password'],
                'role' => 'admin',
            ]
        );
    }
}
