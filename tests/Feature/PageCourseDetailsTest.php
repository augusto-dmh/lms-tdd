<?php

use App\Models\Course;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;
use function Pest\Laravel\withoutExceptionHandling;

uses(RefreshDatabase::class);

it('does not find unreleased courses', function () {
    // Arrange
    $unreleasedCourse = Course::factory()->create();

    // Assert & Act
    get(route('course-details', $unreleasedCourse))
        ->assertNotFound();
});

it('shows course details', function () {
    withoutExceptionHandling();

    // Arrange
    $course = Course::factory()->released()->create();

    // Act & Assert
    get(route('course-details', $course))
        ->assertSeeText([
            $course->title,
            $course->description,
            $course->tagline,
            ...$course->learnings,
        ])
        ->assertSee(asset("images/$course->image_name"));
});

it('shows course video count', function () {
    // Arrange
    $course = Course::factory()
        ->released()
        ->has(Video::factory()->count(3)->state(['title' => 'Video Title']))
        ->create()
        ->load('videos');

    // Act & Assert
    get(route('course-details', $course))
        ->assertSeeText('3 videos')
        ->assertViewHas(
            'course',
            function (Course $course) {
                return $course->videos->every(fn (Video $v) => $v->title === 'Video Title');
            }
        );
});
