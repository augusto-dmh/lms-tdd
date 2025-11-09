<?php

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;
use function Pest\Laravel\withoutExceptionHandling;

uses(RefreshDatabase::class);

it('gives back successful response for home page', function () {
    withoutExceptionHandling();

    // Act & Assert
    get(route('home'))
        ->assertOk();
});

it('gives back successful response for course details page', function () {
    // Arrange
    $course = Course::factory()->create();

    // Act & Assert
    get(route('course-details', $course))
        ->assertOk();
});
