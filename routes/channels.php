<?php

use Illuminate\Support\Facades\Broadcast;
use App\Http\Resources\UserResource;
//this default
Broadcast::channel('online', function ($user) {
    return $user ? new UserResource($user): null;
    //auth mecha
    //false (not auth) makes join request 403
    //Iwant to return auth user cause I need it in channel listen in chatLayout
});
//it is better to put returned user in resource cauze data like email_verified_at is very sensitive to front
