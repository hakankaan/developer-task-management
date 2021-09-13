<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeveloperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $developerList = collect([
            ['name' => 'DEV1', 'difficulty' => 1],
            ['name' => 'DEV2', 'difficulty' => 2],
            ['name' => 'DEV3', 'difficulty' => 3],
            ['name' => 'DEV4', 'difficulty' => 4],
            ['name' => 'DEV5', 'difficulty' => 5],
            // ['name' => 'DEV6', 'difficulty' => 4],
            // ['name' => 'DEV7', 'difficulty' => 5],
            // ['name' => 'DEV8', 'difficulty' => 3],
            // ['name' => 'DEV9', 'difficulty' => 4],
            // ['name' => 'DEV10', 'difficulty' => 5],
        ]);
        foreach ($developerList as $key => $value) {
            DB::table('developers')->insert([
                'name' => $value['name'],
                'difficulty' => $value['difficulty']
            ]);
        }
    }
}
