<?php

namespace App\Providers;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\ApiClients\NullTwitterApiClient;
use App\ApiClients\TwitterApiClient;
use App\Interfaces\TwitterApiClientInterface;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class TwitterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(TwitterOAuth::class, function () {
            $client = new TwitterOAuth(
                consumerKey: config('services.twitter.consumer_key'),
                consumerSecret: config('services.twitter.consumer_secret'),
                oauthToken: config('services.twitter.access_token'),
                oauthTokenSecret: config('services.twitter.access_token_secret'),
            );

            $client->setApiVersion('2');

            return $client;
        });

        $this->app->singleton(TwitterApiClientInterface::class, function (Application $app) {
            if ($app->environment() === 'production') {
                return app(TwitterApiClient::class);
            }

            return new NullTwitterApiClient();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
