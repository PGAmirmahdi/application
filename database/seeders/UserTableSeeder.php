<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Amirmahdi',
            'family' => 'Asadi',
            'role' => 'admin',
            'phone' => '09336533433',
            'password' => bcrypt('1881374'),
            'national_code' => '2640263579',
        ]);
    }
}
