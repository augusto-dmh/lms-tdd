<?php

use App\Mail\PaymentSuccessMail;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

it('has expected content', function () {
    // Arrange
    Mail::fake();
    config()->set('app.name', 'someApp');
    $course = Course::factory()->create();
    $mail = new PaymentSuccessMail($course);

    // Assert
    $mail
        ->assertSeeInOrderInText([
            "Thanks for purchasing \"$course->title\"!",
            'If it is your first time just click the button below and reset your password.',
            'Login',
            route('login'),
            'Thanks,',
            'someApp',
        ]);
});
