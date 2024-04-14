<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('service-channel.{service}', function ($user, $service) {
   
    return (int) $user->service->id === (int) $service;
    // return true;
});

Broadcast::channel('game', function () {
    return true;
});

Broadcast::channel('player-channel', function () {
    return true;
});
