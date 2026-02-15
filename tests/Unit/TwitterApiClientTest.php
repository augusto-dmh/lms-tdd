<?php

use Abraham\TwitterOAuth\TwitterOAuth;
use App\ApiClients\TwitterApiClient;

it('calls oauth client for a tweet', function () {
    // Assert
    /** @var TwitterOAuth&\Mockery\MockInterface $mockedTwitterOAuth */
    $mockedTwitterOAuth = mock(TwitterOAuth::class)
        ->shouldReceive('post')
        ->once()
        ->withArgs(['tweets', ['text' => 'My tweet message']])
        ->andReturn(getPostSuccessReturn())
        ->getMock();

    // Arrange
    $client = new TwitterApiClient($mockedTwitterOAuth);

    // Act & Assert
    expect($client->tweet('My tweet message'))
        ->toEqual(getPostSuccessReturn());
});

/** @return array{data: array{id: string, text: string}} */
function getPostSuccessReturn(): array
{
    return [
        'data' => [
            'id' => '1346889436626259968',
            'text' => 'My tweet message',
        ],
    ];
}
