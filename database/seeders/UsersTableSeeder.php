<?php

namespace Database\Seeders;

use App\Models\Users;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'userID' => 1,
                'user_type' => 0, // admin
                'email' => 'admin@example.com',
                'password' => bcrypt("adminpassword"),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'userID' => 2,
                'user_type' => 2, // librarian
                'email' => 'librarian@example.com',
                'password' => bcrypt('librarianpassword'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'userID' => 3,
                'user_type' => 1, // assistant librarian
                'email' => 'assistantlibrarian@example.com',
                'password' => bcrypt('assistantpassword'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            Users::create($user);
        }
    }
}
