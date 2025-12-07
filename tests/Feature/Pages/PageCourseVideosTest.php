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
        ->has(Video::factory()->state(['title' => 'My video']))
        ->create();

    // Act & Assert
    loginAsUser()
        ->get(route('page.course-videos', $course))
        ->assertOk()
        ->assertSeeText('My video');
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

it('shows details for given video', function () {
    // Arrange
    $course = Course::factory()
        ->has(Video::factory()->state([
            'title' => 'Video title',
            'description' => 'Video description',
            'duration' => 10,
        ]))->create();

    // Act & Assert
    Livewire::test(VideoPlayer::class, ['video' => $course->videos->first()])
        ->assertSeeText([
            'Video title',
            'Video description',
            '10min'
        ]);
});

it('shows given video', function () {
    // Arrange
    $course = Course::factory()
        ->has(Video::factory()->state([
            'vimeo_id' => 'vimeo-id',
        ]))->create();

    // Act & Assert
    Livewire::test(VideoPlayer::class, ['video' => $course->videos->first()])
        ->assertSee('<iframe src="https://player.vimeo.com/video/vimeo-id"', false);
});
