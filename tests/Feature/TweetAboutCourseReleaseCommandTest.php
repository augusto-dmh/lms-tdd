<?php

use App\Console\Commands\TweetAboutCourseReleaseCommand;
use App\Models\Course;

use function Pest\Laravel\artisan;
use Twitter;

it('tweets about release for provided course', function () {
    // Arrange
    Twitter::fake();
    $course = Course::factory()->create();

    // Act
    artisan(TweetAboutCourseReleaseCommand::class, ['courseId' => $course->id]);

    // Assert
    Twitter::assertTweetSent("I just released $course->title 🎉 Check it out on " . route('pages.course-details', $course));
});
