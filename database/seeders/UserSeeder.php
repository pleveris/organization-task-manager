<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Currently not needed; keeping it here for now, just in case...
     */
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'first_name'        => 'admin',
            'last_name'         => 'Test',
            'email'             => 'admin@admin.com',
            'email_verified_at' => now(),
            'password'          => bcrypt('password123'),
            'remember_token'    => Str::random(10),
        ]);

        $admin->assignRole('admin');

        /*$user = User::create([
            'first_name' => 'user',
            'last_name' => 'will be',
            'email' => 'user@user.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        $user->assignRole('user');

        User::factory()->count(10)->create();
        User::factory()->count(20)->deleted()->create();*/
    }
}
