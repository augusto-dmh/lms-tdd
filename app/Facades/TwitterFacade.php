<?php

namespace App\Facades;

use App\Interfaces\TwitterApiClientInterface;
use Illuminate\Support\Facades\Facade;
use Tests\Feature\Fakes\TwitterFake;

class TwitterFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TwitterApiClientInterface::class;
    }

    public static function fake()
    {
        self::swap(new TwitterFake);
    }
}
