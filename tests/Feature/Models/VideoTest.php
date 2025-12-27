<?php

use App\Models\Course;
use App\Models\User;
use App\Models\Video;
use App\Models\WatchedVideo;

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
        ->hasAttached(User::factory()->count(2), relationship: 'watchers')
        ->create();

    expect($video->watchers)
        ->toHaveCount(2)
        ->each->toBeInstanceOf(User::class);
});

it('tells if it has been watched by current user', function () {
    // Arrange
    $user = User::factory()->create();

    $watchedVideo = Video::factory()
        ->hasAttached($user, relationship: 'watchers')
        ->create();
    $unwatchedVideo = Video::factory()->create();

    // Act & Assert
    loginAsUser($user);
    expect($watchedVideo->alreadyWatchedByCurrentUser())
        ->toBe(true);
    expect($unwatchedVideo->alreadyWatchedByCurrentUser())
        ->toBe(false);
});
