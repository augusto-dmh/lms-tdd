<?php

use App\Models\Course;
use Carbon\Carbon;
use Juampi92\TestSEO\TestSEO;

use function Pest\Laravel\get;
use function Pest\Laravel\withoutExceptionHandling;

it('sees courses overview', function () {
    withoutExceptionHandling();

    // Arrange
    $firstCourse = Course::factory()->released()->create();
    $lastCourse = Course::factory()->released()->create();

    // Act & Assert
    get(route('pages.home'))
        ->assertSeeText([
            $firstCourse->title,
            $firstCourse->description,
            $lastCourse->title,
            $lastCourse->description,
        ])
        ->assertOk();
});

it('shows only released courses', function () {
    // Arrange
    $releasedCourse = Course::factory()->released()->create();
    $notReleasedCourse = Course::factory()->create();

    // Act & Assert
    get(route('pages.home'))
        ->assertSeeText($releasedCourse->title)
        ->assertDontSeeText($notReleasedCourse->title);
});

it('shows courses by release date', function () {
    // Arrange
    $course = Course::factory()->released(Carbon::yesterday())->create();
    $newestCourse = Course::factory()->released(Carbon::today())->create();

    // Act & Assert
    get(route('pages.home'))
        ->assertSeeInOrder([
            $newestCourse->title,
            $course->title,
        ]);
});

it('includes login if not logged in', function () {
    // Act & Assert
    get(route('pages.home'))
        ->assertOk()
        ->assertSeeText('Login')
        ->assertSee(route('login'));
});

it('includes logout if logged in', function () {
    // Act & Assert
    loginAsUser()
        ->get(route('pages.home'))
        ->assertOk()
        ->assertSeeText('Log out')
        ->assertSee(route('logout'));
});

it('does not find JetStream registration page', function () {
    // Act & Assert
    get('register')->assertNotFound();
});

it('includes courses links', function () {
    // Arrange
    $firstCourse = Course::factory()->released()->create();
    $secondCourse = Course::factory()->released()->create();
    $lastCourse = Course::factory()->released()->create();

    // Act & Assert
    get(route('pages.home'))
        ->assertOk()
        ->assertSee([
            route('pages.course-details', $firstCourse),
            route('pages.course-details', $secondCourse),
            route('pages.course-details', $lastCourse),
        ]);
});

it('includes a page title', function () {
    // Arrange
    $expectedTitle = config('app.name') . ' - Home';

    // Act & Assert
    $response = get(route('pages.home'))
        ->assertOk();

    // Assertion
    $seo = new TestSEO($response->getContent());
    expect($seo->data->title())
        ->toBe($expectedTitle);
});

it('includes social tags', function () {
    // Arrange
    $appName = config('app.name');
    $description = "$appName is the leading learning platform for Laravel developers.";

    // Act
    $response = get(route('pages.home'))
        ->assertOk();

    // Assert
    $seo = new TestSEO($response->getContent());
    expect($seo->data)
        ->description()->toBe($description)
        ->openGraph()->type->toBe('website')
        ->openGraph()->url->toBe(route('pages.home'))
        ->openGraph()->title->toBe($appName)
        ->openGraph()->description->toBe($description)
        ->openGraph()->image->toBe(asset('images/social.png'))
        ->twitter()->card->toBe('summary_large_image');
});
