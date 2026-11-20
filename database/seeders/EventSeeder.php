<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('events')->insert([
            [
                'organizer_id' => 2,
                'category_id' => 1,
                'title' => 'Seminar Teknologi AI 2026',
                'description' => 'Seminar mengenai perkembangan dan penerapan Artificial Intelligence di dunia pendidikan.',
                'date' => now()->addDays(5)->toDateString(),
                'location' => 'Auditorium Kampus',
                'poster' => null,
                'registration_url' => 'https://example.com/daftar-ai',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizer_id' => 3,
                'category_id' => 2,
                'title' => 'Workshop Web Development',
                'description' => 'Workshop pembuatan website menggunakan Laravel dan MySQL.',
                'date' => now()->addDays(10)->toDateString(),
                'location' => 'Lab Komputer',
                'poster' => null,
                'registration_url' => 'https://example.com/daftar-web',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizer_id' => 2,
                'category_id' => 3,
                'title' => 'Lomba UI/UX Design',
                'description' => 'Kompetisi desain antarmuka aplikasi untuk mahasiswa.',
                'date' => now()->addDays(15)->toDateString(),
                'location' => 'Gedung Fakultas',
                'poster' => null,
                'registration_url' => 'https://example.com/lomba-uiux',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizer_id' => 4,
                'category_id' => 4,
                'title' => 'Webinar Cyber Security',
                'description' => 'Mengenal dasar keamanan jaringan dan perlindungan data digital.',
                'date' => now()->addDays(20)->toDateString(),
                'location' => 'Online',
                'poster' => null,
                'registration_url' => 'https://example.com/webinar-security',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizer_id' => 3,
                'category_id' => 5,
                'title' => 'Open Recruitment UKM',
                'description' => 'Penerimaan anggota baru Unit Kegiatan Mahasiswa.',
                'date' => now()->addDays(25)->toDateString(),
                'location' => 'Student Center',
                'poster' => null,
                'registration_url' => null,
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizer_id' => 4,
                'category_id' => 6,
                'title' => 'Aksi Peduli Lingkungan',
                'description' => 'Kegiatan sosial mahasiswa berupa penanaman pohon di lingkungan kampus.',
                'date' => now()->addDays(30)->toDateString(),
                'location' => 'Area Kampus',
                'poster' => null,
                'registration_url' => null,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
