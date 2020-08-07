<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WorkTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('works')->insert([[
            'user_id' => 4,
            'name' => "Work 6",
            'phone' => "1234567890",
            'min_budget'=>'10000',
            'max_budget'=>'15000',
            'description'=>'Test Description',
            'image'=>'default.jpg'
        ],
        [
            'user_id' => 4,
            'name' => "Work 7",
            'phone' => "1234567890",
            'min_budget'=>'13000',
            'max_budget'=>'18000',
            'description'=>'Test Description',
            'image'=>'default.jpg'
        ],
            [
                'user_id' => 4,
                'name' => "Work 8",
                'phone' => "1234567890",
                'min_budget'=>'13000',
                'max_budget'=>'18000',
                'description'=>'Test Description',
                'image'=>'default.jpg'
            ],
            [
                'user_id' => 4,
                'name' => "Work 9",
                'phone' => "1234567890",
                'min_budget'=>'40000',
                'max_budget'=>'60000',
                'description'=>'Test Description',
                'image'=>'default.jpg'
            ],
            [
                'user_id' => 1,
                'name' => "Work 10",
                'phone' => "1234567890",
                'min_budget'=>'50000',
                'max_budget'=>'70000',
                'description'=>'Test Description',
                'image'=>'default.jpg'
            ],
            [
                'user_id' => 1,
                'name' => "Work 11",
                'phone' => "1234567890",
                'min_budget'=>'25000',
                'max_budget'=>'50000',
                'description'=>'Test Description',
                'image'=>'default.jpg'
            ],
            [
                'user_id' => 2,
                'name' => "Work 12",
                'phone' => "1234567890",
                'min_budget'=>'30000',
                'max_budget'=>'40000',
                'description'=>'Test Description',
                'image'=>'default.jpg'
            ],
            [
                'user_id' => 3,
                'name' => "Work 13",
                'phone' => "1234567890",
                'min_budget'=>'19000',
                'max_budget'=>'24000',
                'description'=>'Test Description',
                'image'=>'default.jpg'
            ],
            [
                'user_id' => 3,
                'name' => "Work 14",
                'phone' => "1234567890",
                'min_budget'=>'23000',
                'max_budget'=>'28000',
                'description'=>'Test Description',
                'image'=>'default.jpg'
            ]]);
    }
}
