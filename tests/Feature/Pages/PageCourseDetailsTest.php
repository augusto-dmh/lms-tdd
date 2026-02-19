<?php

use App\Models\Course;
use App\Models\Video;
use Juampi92\TestSEO\TestSEO;

use function Pest\Laravel\get;
use function Pest\Laravel\withoutExceptionHandling;

it('does not find unreleased courses', function () {
    // Arrange
    $unreleasedCourse = Course::factory()->create();

    // Assert & Act
    get(route('pages.course-details', $unreleasedCourse))
        ->assertNotFound();
});

it('shows course details', function () {
    withoutExceptionHandling();

    // Arrange
    $course = Course::factory()->released()->create();

    // Act & Assert
    get(route('pages.course-details', $course))
        ->assertSeeText([
            $course->title,
            $course->description,
            $course->tagline,
            ...$course->learnings,
        ])
        ->assertSee(asset("images/$course->image_name"));
});

it('shows course video count', function () {
    // Arrange
    $course = Course::factory()
        ->released()
        ->has(Video::factory()->count(3)->state(['title' => 'Video Title']))
        ->create()
        ->load('videos');

    // Act & Assert
    get(route('pages.course-details', $course))
        ->assertSeeText('3 videos')
        ->assertViewHas(
            'course',
            function (Course $course) {
                return $course->videos->every(fn(Video $v) => $v->title === 'Video Title');
            }
        );
});

it('includes paddle checkout button', function () {
    // Arrange
    withoutExceptionHandling();
    config()->set('services.paddle.client_token', 'test_163fb3f24cdb0f2hbk4zbff3eh6');

    $course = Course::factory()
        ->released()
        ->state(['paddle_price_id' => 'pri_01kdasatkyahkkblzy6hh0txmqhw'])
        ->create();

    get(route('pages.course-details', $course))
        ->assertOk()
        ->assertSeeInOrder([
            '<script src="https://cdn.paddle.com/paddle/v2/paddle.js"></script>',

            '<script type="text/javascript">',
            'Paddle.Initialize({',
            'token: "test_163fb3f24cdb0f2hbk4zbff3eh6"',

            'function openCheckout() {',
            'Paddle.Checkout.open(checkoutOptions);',

            '<a href="#" onclick="openCheckout()">Buy Now!</a>',
        ], false);
});

it('includes a content title', function () {
    // Arrange
    $course = Course::factory()->released()->create();
    $expectedTitle = "$course->title - " . config('app.name');

    // Act
    $response = get(route('pages.course-details', $course))
        ->assertOk();

    // Assertion
    $seo = new TestSEO($response->getContent());
    expect($seo->data->title())
        ->toBe($expectedTitle);
});

it('includes social tags', function () {
    // Arrange
    $course = Course::factory()->released()->create();

    // Act
    $response = get(route('pages.course-details', $course))
        ->assertOk();

    // Assert
    $seo = new TestSEO($response->getContent());
    expect($seo->data)
        ->description()->toBe($course->description)
        ->openGraph()->type->toBe('website')
        ->openGraph()->url->toBe(route('pages.course-details', $course))
        ->openGraph()->title->toBe($course->title)
        ->openGraph()->description->toBe($course->description)
        ->openGraph()->image->toBe(asset("images/$course->image_name"))
        ->twitter()->card->toBe('summary_large_image');
});
