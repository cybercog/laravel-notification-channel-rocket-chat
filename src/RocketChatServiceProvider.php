<?php

namespace NotificationChannels\RocketChat;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;

class RocketChatServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(RocketChatWebhookChannel::class)
            ->needs(RocketChat::class)
            ->give(function () {
                return new RocketChat(
                    new HttpClient,
                    config('services.rocketchat.url'),
                    config('services.rocketchat.token'),
                    config('services.rocketchat.room')
                );
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
