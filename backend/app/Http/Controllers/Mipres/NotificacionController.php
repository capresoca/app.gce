<?php

namespace App\Http\Controllers\Mipres;

use App\Models\Mipres\MpNotificacion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;

class NotificacionController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'mp_direccionamiento_id' => 'required|exists:mp_direccionamientos,id',
            'tipo'  => 'required|in:sms,telefónica,email,personal,otro',
            'notificacion_exitosa' => 'required|boolean',
            'aceptada' => 'required|boolean'
        ];
        $this->validate($request, $rules);

        $notificacion = MpNotificacion::create($request->all());

        return new Resource($notificacion);

    }
}
