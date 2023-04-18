<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['id' => 1, 'name' => 'Super Admin', 'email' => 'superadmin@gmail.com', 'phone_number' => '+923226983833', 'password' => bcrypt('12345678'), 'is_active' => 1, 'is_approved' => 1, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id' => 2, 'name' => 'Site User', 'email' => 'siteviewer@gmail.com', 'phone_number' => '+923456983833', 'password' => bcrypt('12345678'), 'is_active' => 1, 'is_approved' => 1, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ];
        DB::table('users')->insert($users);
    }
}
