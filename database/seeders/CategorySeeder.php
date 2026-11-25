<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Seminar', 'description' => 'Academic talks, guest lectures, and campus seminars.', 'is_active' => true],
            ['name' => 'Workshop', 'description' => 'Hands-on training and skill-building sessions.', 'is_active' => true],
            ['name' => 'Competition', 'description' => 'Hackathons, debates, and student competitions.', 'is_active' => true],
            ['name' => 'Sports', 'description' => 'Tournaments, fun runs, and campus athletic events.', 'is_active' => true],
            ['name' => 'Arts & Culture', 'description' => 'Exhibitions, music performances, and cultural festivals.', 'is_active' => true],
            ['name' => 'Community Service', 'description' => 'Volunteering, charity drives, and social outreach.', 'is_active' => true],
        ];

        foreach ($categories as $item) {
            Category::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'is_active' => $item['is_active'],
                ]
            );
        }
    }
}