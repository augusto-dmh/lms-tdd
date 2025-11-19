<?php

use App\Models\Course;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('cannot be accessed by guest', function () {
    // Act & Assert
    get(route('dashboard'))
        ->assertRedirectToRoute('login');
});

it('lists purchased courses', function () {
    // Arrange
    $user = User::factory()
        ->has(Course::factory()->count(2)->state(new Sequence(
            ['title' => 'Course A'],
            ['title' => 'Course B'],
        )))
        ->create();

    // Act & Assert
    actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSeeText([
            'Course A',
            'Course B',
        ]);
});

it('shows latest purchased course first', function () {
    // Arrange
    $user = User::factory()
        ->hasAttached(
            Course::factory()->state(new Sequence(
                ['title' => 'Course Purchased Yesterday'],
                ['title' => 'Course Purchased Today'],
            ))->released()->count(2),
            new Sequence(
                ['created_at' => Carbon::yesterday()],
                ['created_at' => Carbon::now()],
            ),
            'courses'
        )
        ->create();

    // Act & Assert
    actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSeeTextInOrder([
            'Course Purchased Today',
            'Course Purchased Yesterday',
        ]);
});

it('includes link to product videos', function () {
    // Arrange
    $course = Course::factory()
        ->hasAttached(User::factory())
        ->has(Video::factory()->state(
            ['link' => 'http://some-link.com']
        ))
        ->create();

    // Act & Assert
    actingAs($course->users->first())
        ->get(route('dashboard'))
        ->assertSee('http://some-link.com');
});
