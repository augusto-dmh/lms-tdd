<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;
use function Pest\Laravel\withoutExceptionHandling;

use App\Models\Course;

uses(RefreshDatabase::class);

it('sees courses overview', function () {
    withoutExceptionHandling();

    // Arrange
    Course::factory()->create(['title' => 'Course A', 'description' => 'Description A']);
    Course::factory()->create(['title' => 'Course B', 'description' => 'Description B']);

    // Act & Assert
    get(route('home'))->dump()
        ->assertSeeText([
            'Course A',
            'Description A',
            'Course B',
            'Description B',
        ])
        ->assertOk();
});
