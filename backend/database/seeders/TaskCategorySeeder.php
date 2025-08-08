<?php

namespace Database\Seeders;

use App\Models\TaskCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Work',
            'Personal',
            'Urgent',
        ];

        foreach ($categories as $category) {
            TaskCategory::create([
                'name' => $category,
            ]);
        }
    }
}
