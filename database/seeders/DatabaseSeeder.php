<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Course;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AddGivenCoursesSeeder::class,
            AddGivenVideosSeeder::class,
            AddLocalTestUserSeeder::class,
        ]);

        $testUser = User::query()->first();
        $courses = Course::query()->get();
        $videos = Video::query()->get();

        $testUser->purchasedCourses()->syncWithoutDetaching($courses);
        $testUser->watchedVideos()->syncWithoutDetaching($videos);
    }
}
