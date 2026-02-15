<?php

use App\ApiClients\NullTwitterApiClient;

it('returns empty array for a tweet call', function () {
    $client = new NullTwitterApiClient();

    expect($client->tweet('some text'))
        ->toBe([]);
});
