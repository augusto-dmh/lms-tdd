<?php

use App\ApiClients\NullTwitterApiClient;

it('returns empty array for a tweet call', function () {
    // Arrange
    $client = new NullTwitterApiClient();

    // Act & Assert
    expect($client->tweet('some text'))
        ->toBe([]);
});
