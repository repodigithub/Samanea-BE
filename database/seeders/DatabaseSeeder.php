<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        $faker = Faker::create('id_ID');
        
        for($i = 1; $i <= 40; $i++){
            
            DB::table('users')->insert([
                'fullname' => $faker->name,
                'email' => $faker->email,
                'telphone' => $faker->phoneNumber,
                'password' => Hash::make('password'),
                'level' => 'supervisor',
                'status' => 'wait_approval'
            ]);
            // $this->call('UsersTableSeeder');
        }
    }
}
    