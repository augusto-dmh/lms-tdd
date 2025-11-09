<?php

use function Pest\Laravel\get;
use function Pest\Laravel\withoutExceptionHandling;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('gives back successful response for home page', function () {
    withoutExceptionHandling();

    // Act & Assert
    get(route('home'))
        ->assertOk();
});
