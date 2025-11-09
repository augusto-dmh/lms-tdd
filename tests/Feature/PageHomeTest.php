<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;
use function Pest\Laravel\withoutExceptionHandling;

use App\Models\Course;
use Carbon\Carbon;

uses(RefreshDatabase::class);

it('sees courses overview', function () {
    withoutExceptionHandling();

    // Arrange
    Course::factory()->create(['title' => 'Course A', 'description' => 'Description A', 'released_at' => Carbon::now()]);
    Course::factory()->create(['title' => 'Course B', 'description' => 'Description B', 'released_at' => Carbon::now()]);

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

it('shows only released courses', function () {
    // Arrange
    Course::factory()->create(['title' => 'Released', 'released_at' => Carbon::now()]);
    Course::factory()->create(['title' => 'Not Released']);

    // Act & Assert
    get(route('home'))
        ->assertSeeText('Released')
        ->assertDontSeeText('Not Released');
});


it('shows courses by release date', function () {
    // Arrange
    Course::factory()->create(['title' => 'Released Yesterday', 'released_at' => Carbon::yesterday()]);
    Course::factory()->create(['title' => 'Released Today', 'released_at' => Carbon::now()]);

    // Act & Assert
    get(route('home'))
        ->assertSeeInOrder([
            'Released Today',
            'Released Yesterday'
        ]);
});
