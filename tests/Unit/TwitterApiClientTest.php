<?php

use Abraham\TwitterOAuth\TwitterOAuth;
use App\ApiClients\TwitterApiClient;

it('calls oauth client for a tweet', function () {
    $ouathClientMock = mock(TwitterOAuth::class);
    $client = new TwitterApiClient($ouathClientMock);

    $ouathClientMock
        ->shouldReceive('post')
        ->once();

    $client->tweet('some text');
});
