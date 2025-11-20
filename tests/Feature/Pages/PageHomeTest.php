<?php

use App\Models\Course;
use Carbon\Carbon;

use function Pest\Laravel\get;
use function Pest\Laravel\withoutExceptionHandling;

it('sees courses overview', function () {
    withoutExceptionHandling();

    // Arrange
    $firstCourse = Course::factory()->released()->create();
    $lastCourse = Course::factory()->released()->create();

    // Act & Assert
    get(route('pages.home'))
        ->assertSeeText([
            $firstCourse->title,
            $firstCourse->description,
            $lastCourse->title,
            $lastCourse->description,
        ])
        ->assertOk();
});

it('shows only released courses', function () {
    // Arrange
    $releasedCourse = Course::factory()->released()->create();
    $notReleasedCourse = Course::factory()->create();

    // Act & Assert
    get(route('pages.home'))
        ->assertSeeText($releasedCourse->title)
        ->assertDontSeeText($notReleasedCourse->title);
});

it('shows courses by release date', function () {
    // Arrange
    $course = Course::factory()->released(Carbon::yesterday())->create();
    $newestCourse = Course::factory()->released(Carbon::today())->create();

    // Act & Assert
    get(route('pages.home'))
        ->assertSeeInOrder([
            $newestCourse->title,
            $course->title,
        ]);
});
