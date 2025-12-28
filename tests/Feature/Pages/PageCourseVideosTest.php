<?php

use App\Livewire\VideoPlayer;
use App\Models\Course;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Livewire\Livewire;

use function Pest\Laravel\get;

it('cannot be accessed by guest', function () {
    // Arrange
    $course = Course::factory()->create();

    // Act & Assert
    get(route('page.course-videos', $course))
        ->assertRedirectToRoute('login');
});

it('includes video player', function () {
    // Arrange
    $course = Course::factory()
        ->has(Video::factory())
        ->create();

    // Act & Assert
    loginAsUser()
        ->get(route('page.course-videos', $course))
        ->assertSeeLivewire(VideoPlayer::class);
});

it('shows first course video by default', function () {
    // Arrange
    $course = Course::factory()
        ->has(Video::factory())
        ->create();

    // Act & Assert
    loginAsUser()
        ->get(route('page.course-videos', $course))
        ->assertOk()
        ->assertSee("<h3>{$course->videos()->first()->title}", false);
});

it('shows provided course video', function () {
    // Arrange
    $course = Course::factory()
        ->has(
            Video::factory()
                ->state(new Sequence(['title' => 'First Video'], ['title' => 'Second Video']))
                ->count(2)
        )
        ->create();

    // Act & Assert
    loginAsUser()
        ->get(route('page.course-videos', [
            'course' => $course,
            'video' => $course->videos()->orderByDesc('id')->first(),
        ]))
        ->assertOk()
        ->assertSeeText('Second Video');
});
