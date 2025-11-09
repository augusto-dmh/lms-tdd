<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Course;

uses(RefreshDatabase::class);

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
