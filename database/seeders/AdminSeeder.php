<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();
        DB::table('users')->insert([
            'name'              => 'Shubham Chugh',
            'email'             => 'chugh.shubham12@gmail.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('Unknown@24'),
        ]);
    }
}
