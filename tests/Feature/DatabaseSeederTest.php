<?php

use function Pest\Laravel\artisan;
use function Pest\Laravel\assertDatabaseCount;
use App\Models\Course;

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
