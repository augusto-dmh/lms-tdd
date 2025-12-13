<?php

use App\Models\Course;
use App\Models\User;
use App\Models\Video;

it('gives back readable video duration', function () {
    // Arrange
    $video = Video::factory()->state(['duration_in_min' => 10])->create();

    // Act & Assert
    expect($video->getReadableDuration())
        ->toBe('10min');
});

it('belongs to a course', function () {
    // Arrange
    $video = Video::factory()
        ->has(Course::factory())
        ->create();

    expect($video->course)
        ->toBeInstanceOf(Course::class);
});

it('belongs to many users', function () {
    $video = Video::factory()
        ->hasAttached(User::factory()->count(2))
        ->create();

    expect($video->users)
        ->toHaveCount(2)
        ->each->toBeInstanceOf(User::class);
});
