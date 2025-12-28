<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class AddGivenCoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::updateOrCreate(
            ['slug' => Str::of('Laravel For Beginners')->slug()],
            [
                'title' => 'Laravel For Beginners',
                'paddle_product_id' => '123123',
                'tagline' => 'Make your first steps as a Laravel dev.',
                'description' => 'A video course to teach you Laravel from scratch. We will cover the basics and build a real app.',
                'image_name' => 'laravel_for_beginners.png',
                'learnings' => [
                    'How to start with Laravel',
                    'Where to start with Laravel',
                    'Build your first Laravel application',
                ],
                'released_at' => now(),
            ]
        );

        Course::updateOrcreate(
            ['slug' => Str::of('Advanced Laravel')->slug()],
            [
                'title' => 'Advanced Laravel',
                'paddle_product_id' => '123123',
                'tagline' => 'Level up as a Laravel developer.',
                'description' => 'A video course to teach you advanced techniques in Laravel',
                'image_name' => 'advanced_laravel.png',
                'learnings' => [
                    'How to use the service container',
                    'Pipelines in Laravel',
                    'Secure your application',
                ],
                'released_at' => now()
            ],
        );

        Course::updateOrCreate(
            ['slug' => Str::of('TDD The Laravel Way')->slug()],
            [
                'title' => 'TDD The Laravel Way',
                'paddle_product_id' => '123123',
                'tagline' => 'Learn to build Laravel apps with TDD.',
                'description' => 'A hands-on course on Test Driven Development in Laravel.',
                'image_name' => 'tdd_the_laravel_way.png',
                'learnings' => [
                    'Write tests first',
                    'Use PHPUnit and Pest',
                    'Design for testability',
                ],
                'released_at' => now(),
            ]
        );
    }
}
