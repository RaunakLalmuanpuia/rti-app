<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {


        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'role' => '10',
            'contact' => '123456701',
            'address' => 'Electric Veng',
            'created_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('users')->insert([
            'name' => 'user',
            'email' => 'user@mail.com',
            'password' => Hash::make('password'),
            'role' => '0',
            'contact' => '123456789',
            'address' => 'Electric Veng',
            'created_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('departments')->insert([
            'name' => "dept1",
            'created_at' => date("Y-m-d H:i:s"),

        ]);
        DB::table('departments')->insert([
            'name' => "dept2",
            'created_at' => date("Y-m-d H:i:s"),

        ]);

        DB::table('users')->insert([
            'name' => 'aspio1',
            'email' => 'aspio1@mail.com',
            'password' => Hash::make('password'),
            'role' => '5',
            'contact' => '1234566777',
            'address' => 'Electric Veng',
            'department' => 1,

            'bio'       => 'aspio',
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('users')->insert([
            'name' => 'aspio2',
            'email' => 'aspio2@mail.com',
            'password' => Hash::make('password'),
            'role' => '5',
            'contact' => '01234567686',
            'address' => 'Electric Veng',
            'department' => 2,

            'bio'       => 'aspio',

            'created_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('users')->insert([
            'name' => 'spio1',
            'email' => 'spio1@mail.com',
            'password' => Hash::make('password'),
            'role' => '5',
            'contact' => '123456780',
            'address' => 'Electric Veng',
            'department' => 1,

            'bio'       => 'spio',
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('users')->insert([
            'name' => 'spio2',
            'email' => 'spio2@mail.com',
            'password' => Hash::make('password'),
            'role' => '5',
            'contact' => '123456781',
            'address' => 'Electric Veng',
            'department' => 2,

            'bio'       => 'spio',

            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('users')->insert([
            'name' => 'daa1',
            'email' => 'daa1@mail.com',
            'password' => Hash::make('password'),
            'role' => '5',
            'contact' => '123456782',
            'address' => 'Electric Veng',
            'department' => 1,

            'bio'       => 'daa',

            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('users')->insert([
            'name' => 'daa2',
            'email' => 'daa2@mail.com',
            'password' => Hash::make('password'),
            'role' => '5',
            'contact' => '123456783',
            'address' => 'Electric Veng',
            'department' => 2,

            'bio'       => 'daa',

            'created_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('users')->insert([
            'name' => 'cic1',
            'email' => 'cic1@mail.com',
            'password' => Hash::make('password'),
            'role' => '5',
            'contact' => '123456123',
            'address' => 'Electric Veng',
            'bio'       => 'cic',
            'created_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('users')->insert([
            'name' => 'cic2',
            'email' => 'cic2@mail.com',
            'password' => Hash::make('password'),
            'role' => '5',
            'contact' => '123456555',
            'address' => 'Electric Veng',
            'bio'       => 'cic',
            'created_at' => date("Y-m-d H:i:s"),
        ]);

        $this->call(DistrictSeeder::class);
    }
}
