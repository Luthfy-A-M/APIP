<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $faker = Faker::create();

        foreach (range(1, 20) as $index) { //dummy for users
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('password'), // Default password for example
                // You can add more fields here
                'email_verified_at' => now(),
                'dept_code' => 'ITBD',
                'remember_token' => Str::random(10),
            ]);
        }

         // Seed departments
         DB::table('departments')->insert([
            'dept_code' => 'ITBD',
            'dept_name' => 'IT Business Department',
            'GL1' => $faker->numberBetween(1, 20),
            'GL2' => $faker->numberBetween(1, 20),
            'GL3' => $faker->numberBetween(1, 20),
            'SPV1' => $faker->numberBetween(1, 20),
            'SPV2' => $faker->numberBetween(1, 20),
            'SPV3' => $faker->numberBetween(1, 20),
            'MGR1' => $faker->numberBetween(1, 20),
            'MGR2' => $faker->numberBetween(1, 20),
            'Dept_Head' => $faker->numberBetween(1, 20),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('departments')->insert([
            'dept_code' => 'CMD',
            'dept_name' => 'Central Maintenance Department',
            'GL1' => $faker->numberBetween(1, 20),
            'GL2' => $faker->numberBetween(1, 20),
            'GL3' => $faker->numberBetween(1, 20),
            'SPV1' => $faker->numberBetween(1, 20),
            'SPV2' => $faker->numberBetween(1, 20),
            'SPV3' => $faker->numberBetween(1, 20),
            'MGR1' => $faker->numberBetween(1, 20),
            'MGR2' => $faker->numberBetween(1, 20),
            'Dept_Head' => $faker->numberBetween(1, 20),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('t_b_m_s')->insert([
            'dept_code' => 'ITBD',
         'section' => 'PC',
          'shift' => '2',
           'date' => now(),
            'time' => '09:00',
             'title' => 'TBM Naik Crane' ,
             'pot_danger_point' => 'Jatoh',
             'most_danger_point' => 'Kecapit Crane',
             'key_word' => 'Hati Hati',
             'prepared_by' => '1',
             'checked_by' => '2',
             'reviewed_by'=>'3',
             'approved1_by'=>'4',
             'approved2_by' => '5',
             'status' => 'draft',
             'created_at' => now(),
            'updated_at' => now(),
        ]);



    }
}
