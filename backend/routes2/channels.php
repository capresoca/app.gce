<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
use App\Models\Aseguramiento\AsBduaproceso;
use App\Models\Aseguramiento\AsBduarespuesta;
use App\User;

Broadcast::channel('bdua-proceso', function ($user) {
    //$proceso = AsBduaproceso::find($id)->first();
    return $user;
    //return empty($proceso);
});

Broadcast::channel('bdua-proceso.{id}', function ($user, $id) {
    $proceso = AsBduaproceso::find($id);
    return $proceso;
});

Broadcast::channel('bdua-respuesta.{id}', function ($user, $id) {
    $bduarespuesta = AsBduarespuesta::find($id);
    return $bduarespuesta;
});

Broadcast::channel('user.{id}', function ($user, $id) {
    $user = User::find($id);
    return $user;
});