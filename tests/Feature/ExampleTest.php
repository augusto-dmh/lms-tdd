<?php

use function Pest\Laravel\get;

it('gives back successful response for home page', function () {
    $response = get('/');

    $response->assertStatus(200);
});
