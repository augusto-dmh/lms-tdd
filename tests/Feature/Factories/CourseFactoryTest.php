<?php

use App\Models\Course;
use Carbon\Carbon;

it('can create a released course by a state', function () {
    // Arrange
    $releasedCourse = Course::factory()->released(Carbon::today())->create();

    // Act & Assert
    expect($releasedCourse->released_at)
        ->toEqual(Carbon::today());
});
