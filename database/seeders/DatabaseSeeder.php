<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
#            $table->enum('role', ['admin', 'author', 'user'])->default('user');

    public function run(): void
    {
        $roles =['admin', 'author', 'user'];
        foreach ($roles as $role) {
            # code...
            $role = Role::create(['name' => $role]);
        }
        $user = User::create([
            "name"=>"dynamite",
            "email"=> "dynamite@gmail.com",
            "password"=>Hash::make("123"),
        ]);
        $user->assignRole('admin');
        $user = User::create([
            "name"=>"madara",
            "email"=> "madara@gmail.com",
            "password"=>Hash::make("123"),
        ]);
        $user->assignRole('author');

        $user = User::create([
            "name"=>"mozan",
            "email"=> "mozan@gmail.com",
            "password"=>Hash::make("123"),
        ]);
        $user->assignRole('user');


        $this->call([
            CategorySeeder::class,
            TagSeeder::class,
            PostSeeder::class,
        ]);
    }
}
