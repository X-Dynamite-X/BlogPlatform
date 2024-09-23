<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for($i = 0 ; $i<=100 ; $i++){
            $user=User::create([
                "name"=>"user".$i,
                "email"=> "user".$i."@gmail.com",
                "password"=>Hash::make("123"),
            ]);
        $user->assignRole('user');
            
        }
    }
}
