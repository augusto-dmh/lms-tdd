<?php

use App\Models\Video;

it('gives back readable video duration', function () {
    // Arrange
    $video = Video::factory()->state(['duration_in_min' => 10])->create();

    // Act & Assert
    expect($video->getReadableDuration())
        ->toBe('10min');
});
