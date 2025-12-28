<?php

use function Pest\Laravel\artisan;
use function Pest\Laravel\assertDatabaseCount;
use App\Models\Course;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\App;

test('adds given courses', function () {
    // Assert
    assertDatabaseCount(Course::class, 0);

    // Act
    artisan('db:seed');

    // Assert
    assertDatabaseCount(Course::class, 3);
});

test('add given courses only once', function () {
    // Assert
    assertDatabaseCount(Course::class, 0);

    // Act
    artisan('db:seed');
    artisan('db:seed');

    // Assert
    assertDatabaseCount(Course::class, 3);
});

it('adds given videos', function () {
    // Assert
    assertDatabaseCount(Video::class, 0);

    // Act
    artisan('db:seed');

    $laravelForBeginnersCourse = Course::where('title', 'Laravel For Beginners')->firstOrFail();
    $advancedLaravelCourse = Course::where('title', 'Advanced Laravel')->firstOrFail();
    $tddCourse = Course::where('title', 'TDD The Laravel Way')->firstOrFail();

    // Assert
    assertDatabaseCount(Video::class, 8);

    expect($laravelForBeginnersCourse)
        ->videos
        ->toHaveCount(3);

    expect($advancedLaravelCourse)
        ->videos
        ->toHaveCount(3);

    expect($tddCourse)
        ->videos
        ->toHaveCount(2);
});

it('adds given videos only once', function () {
    // Assert
    assertDatabaseCount(Video::class, 0);

    // Act
    artisan('db:seed');
    artisan('db:seed');

    // Assert
    assertDatabaseCount(Video::class, 8);
});

it('adds local test user', function () {
    // Arrange
    App::partialMock()->shouldReceive('environment')->andReturn('local');

    // Assert
    assertDatabaseCount(User::class, 0);

    // Act
    artisan('db:seed');

    // Assert
    assertDatabaseCount(User::class, 1);
});

it('adds local test user only once', function () {
    // Arrange
    App::partialMock()->shouldReceive('environment')->andReturn('local');

    // Assert
    assertDatabaseCount(User::class, 0);

    // Act
    artisan('db:seed');
    artisan('db:seed');

    // Assert
    assertDatabaseCount(User::class, 1);
});

it('does not add test user for production', function () {
    // Arrange
    App::partialMock()->shouldReceive('environment')->andReturn('production');

    // Assert
    assertDatabaseCount(User::class, 0);

    // Act
    artisan('db:seed');

    // Assert
    assertDatabaseCount(User::class, 0);
});

it('attaches to the test user default purchased courses and watched videos', function () {
    // Act
    artisan('db:seed');

    // Arrange
    $user = User::query()->first();

    // Assert
    expect($user)
        ->purchasedCourses
        ->toHaveCount(3);
    expect($user)
        ->watchedVideos
        ->toHaveCount(8);
});
