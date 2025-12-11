<?php

use App\Livewire\VideoPlayer;
use App\Models\Course;
use App\Models\Video;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Sequence;

it('shows details for given video', function () {
    // Arrange
    $course = Course::factory()
        ->has(Video::factory())->create();

    // Act & Assert
    $video = $course->videos->first();
    Livewire::test(VideoPlayer::class, ['video' => $video])
        ->assertSeeText([
            $video->title,
            $video->description,
            "({$video->duration_in_min}min)"
        ]);
});

it('shows given video', function () {
    // Arrange
    $course = Course::factory()
        ->has(Video::factory())->create();

    // Act & Assert
    $video = $course->videos->first();
    Livewire::test(VideoPlayer::class, ['video' => $video])
        ->assertSeeHtml('<iframe src="https://player.vimeo.com/video/' . $video->vimeo_id . '"');
});

it('shows list of all course videos', function () {
    // Arrange
    $course = Course::factory()
        ->has(
            Video::factory()
                ->count(2)
                ->state(new Sequence(
                    ['title' => 'First video'],
                    ['title' => 'Second video'],
                ))
        )->create();

    // Act & Assert
    Livewire::test(VideoPlayer::class, ['video' => $course->videos()->first()])
        ->assertSee([
            'First video',
            'Second video',
        ])
        ->assertSeeHtml([
            route('page.course-videos', Video::where('title', 'First video')->first()),
            route('page.course-videos', Video::where('title', 'Second video')->first()),
        ]);
});
