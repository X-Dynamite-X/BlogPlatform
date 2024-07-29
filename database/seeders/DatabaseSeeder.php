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
        $user = User::create([
            "name"=>"dynamite",
            "email"=> "dynamite@gmail.com",
            "password"=>Hash::make("123"),
        ]);
        $roles =['admin', 'author', 'user'];
        foreach ($roles as $role) {
            # code...
            $role = Role::create(['name' => $role]);
        }
        $user->assignRole('admin');
        $this->call([
            CategorySeeder::class,
            TagSeeder::class,
        ]);
    }
}
