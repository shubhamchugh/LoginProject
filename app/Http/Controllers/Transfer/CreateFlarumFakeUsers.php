<?php

namespace App\Http\Controllers\Transfer;

use Illuminate\Http\Request;
use Faker\Generator as Faker;
use App\Models\Flarum\FlarumUsers;
use App\Http\Controllers\Controller;

class CreateFlarumFakeUsers extends Controller
{
    public function create(Request $request, Faker $faker)
    {

        if (!is_numeric($request->userCount)) {
            dd("Please enter numerical Value or Please Add User Count Like http://example.com/insert?userCount=1000");
        }

        $userCount = (!empty($request->userCount)) ? $request->userCount : null;

        if (!empty($userCount)) {
            for ($i = 1; $i <= $userCount; $i++) {

                FlarumUsers::create([
                    'username' => $faker->username,
                    'email'    => $faker->email,
                    'password' => $faker->sha1,
                ]);
            }
        } else {
            dd("Please Add User Count Like http://example.com/flarum-user?userCount=100");
        }
    }
}
