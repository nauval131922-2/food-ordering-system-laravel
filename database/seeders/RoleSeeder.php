<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Role::truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            'waitress', 'chef', 'cashier', 'manager'
        ];

        foreach ($data as $role) {
            Role::insert([
                'name' => $role,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
