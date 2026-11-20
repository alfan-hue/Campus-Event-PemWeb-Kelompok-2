<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('reviews')->insert([
            [
                'event_id' => 1,
                'user_id' => 3,
                'content' => 'Materinya menarik dan menambah wawasan tentang AI.',
                'rating' => 'L',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 1,
                'user_id' => 4,
                'content' => 'Acaranya kurang menarik dan pembahasannya terlalu singkat.',
                'rating' => 'D',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 2,
                'user_id' => 5,
                'content' => 'Workshop sangat bermanfaat untuk belajar Laravel.',
                'rating' => 'L',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 2,
                'user_id' => 2,
                'content' => 'Penjelasan cukup jelas, tetapi masih ada materi yang kurang dibahas.',
                'rating' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 3,
                'user_id' => 5,
                'content' => 'Kompetisi yang menarik untuk mengembangkan kreativitas.',
                'rating' => 'L',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 5,
                'user_id' => 4,
                'content' => 'Informasi pendaftarannya kurang jelas.',
                'rating' => 'D',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
