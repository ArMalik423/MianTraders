<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['id' => 1, 'name' => 'Super admin', 'route' => '/dashboard'],
            ['id' => 2, 'name' => 'Viewer', 'route' => '/dashboard'],
        ];

        DB::table('roles')->insert($roles);
    }
}
