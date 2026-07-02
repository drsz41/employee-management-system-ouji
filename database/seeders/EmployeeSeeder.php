<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // local indonesian name
        $faker = Faker::create('id_ID');

        $employees = [];
        $departments = ['IT', 'Human Resources', 'Finance', 'Marketing', 'Operations', 'Sales'];
        $positions = ['Staff', 'Supervisor', 'Manager', 'Analyst', 'Specialist', 'Lead'];
        $statuses = ['Active', 'Inactive', 'On Leave'];
        // Generate 100 data
        for ($i = 0; $i < 1000; $i++) {
            $employees[] = [
                'employee_id' => 'EMP-' . date('Y') . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'position' => $faker->randomElement($positions),
                'department' => $faker->randomElement($departments),
                'status' => $faker->randomElement($statuses),
                'join_date' => $faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Insert data dalam bentuk chunk untuk performa yang lebih baik dan menghindari memory limit
        // Dibagi menjadi 100 baris per eksekusi query
        foreach (array_chunk($employees, 100) as $chunk) {
            DB::table('employees')->insert($chunk);
        }
    }
}
