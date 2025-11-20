<?php

use App\Models\Course;

use function Pest\Laravel\get;
use function Pest\Laravel\withoutExceptionHandling;

it('gives back successful response for home page', function () {
    withoutExceptionHandling();

    // Act & Assert
    get(route('pages.home'))
        ->assertOk();
});

it('gives back successful response for course details page', function () {
    // Arrange
    $course = Course::factory()->released()->create();

    // Act & Assert
    get(route('pages.course-details', $course))
        ->assertOk();
});

it('gives back succesful response for dashboard page', function () {
    // Act & Assert
    loginAsUser()
        ->get(route('pages.dashboard'))
        ->assertOk();
});
