<?php

namespace App\Http\Controllers;

use App\Models\Count;
use App\Models\FakeUser;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FakeDataInsert extends Controller
{
    public function insert(Request $request)
    {
        if ($request->userCount) {
            if (!is_numeric($request->userCount)) {
                dd("Please enter numerical Value or Please Add User Count Like http://example.com/insert?userCount=1000");
            }
            Schema::disableForeignKeyConstraints();
            FakeUser::truncate();
            Schema::enableForeignKeyConstraints();
            $faker     = Faker::create();
            $userCount = (!empty($request->userCount)) ? $request->userCount : null;

            if (!empty($userCount)) {
                for ($i = 1; $i <= $userCount; $i++) {

                    FakeUser::create([
                        'name' => $faker->name,
                    ]);
                }
            } else {
                dd("Please Add User Count Like http://example.com/insert?userCount=1000");
            }
        }

        if ($request->count) {
            if (!is_numeric($request->count)) {
                dd("Please enter numerical Value or Please Add Count Like http://example.com/insert?count=1000");
            }
            Schema::disableForeignKeyConstraints();
            Count::truncate();
            Schema::enableForeignKeyConstraints();
            ini_set('max_execution_time', '30000');
            ini_set('memory_limit', '3000000000000');
            ini_set('max_input_time', '3000');
            if (!empty($request->count)) {
                for ($i = 1; $i <= $request->count; $i++) {

                    Count::create([
                        'count' => $i,
                    ]);
                }
            } else {
                dd("Please Add Count Like http://example.com/insert?count=1000");
            }
        }

    }
}
