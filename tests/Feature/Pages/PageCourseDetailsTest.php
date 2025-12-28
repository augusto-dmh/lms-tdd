<?php

use App\Models\Course;
use App\Models\Video;

use function Pest\Laravel\get;
use function Pest\Laravel\withoutExceptionHandling;

it('does not find unreleased courses', function () {
    // Arrange
    $unreleasedCourse = Course::factory()->create();

    // Assert & Act
    get(route('pages.course-details', $unreleasedCourse))
        ->assertNotFound();
});

it('shows course details', function () {
    withoutExceptionHandling();

    // Arrange
    $course = Course::factory()->released()->create();

    // Act & Assert
    get(route('pages.course-details', $course))
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
    get(route('pages.course-details', $course))
        ->assertSeeText('3 videos')
        ->assertViewHas(
            'course',
            function (Course $course) {
                return $course->videos->every(fn(Video $v) => $v->title === 'Video Title');
            }
        );
});

it('includes paddle checkout button', function () {
    // Arrange
    config()->set('services.paddle.vendor_id', 'vendor-id');
    $course = Course::factory()
        ->released()
        ->create([
            'paddle_product_id' => 'product-id',
        ]);

    // Act & Assert
    get(route('pages.course-details', $course))
        ->assertOk()
        ->assertSee('<script src="https://cdn.paddle.com/paddle/paddle.js"></script>', false)
        ->assertSee("Paddle.Setup({ vendor: vendor-id });", false)
        ->assertSee('<a href="#!" class="paddle_button" data-product="product-id">Buy Now!</a>', false);
});
