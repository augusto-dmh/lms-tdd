<?php

use App\ApiClients\NullTwitterApiClient;
use App\Interfaces\TwitterApiClientInterface;

it('returns null twitter client for testing env', function () {
    // Arrange
    config()->set('app.env', 'testing');

    // Act & Assert
    expect(app(TwitterApiClientInterface::class))
        ->toBeInstanceOf(NullTwitterApiClient::class);
});
