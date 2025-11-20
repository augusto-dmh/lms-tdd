<?php

use App\Models\Course;
use App\Models\Video;

it('can filter released courses by a scope', function () {
    // Arrange
    Course::factory()->released()->create();

    // Act & Assert
    $coursesStored = Course::query()
        ->released()
        ->get();

    expect($coursesStored)->toHaveCount(1);
    expect($coursesStored->first()->released_at)->not->toBeNull();
});

it('has relationship with videos', function () {
    // Arrange
    $course = Course::factory()->has(Video::factory())->create();
    $course->load('videos');

    // Act & Assert
    expect($course->videos)->not->toBeNull();
    $course
        ->videos
        ->each(fn (Video $v) => expect($v instanceof Video && $v->course_id === $course->id)->toBe(true),
        );
});
