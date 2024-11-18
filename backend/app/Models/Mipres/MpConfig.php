<?php

namespace App\Models\Mipres;

use Illuminate\Database\Eloquent\Model;

class MpConfig extends Model
{
    protected $table = 'mp_config';

    protected $fillable = [
        'dominio',
        'base_url',
        'token_subsididado',
        'token_contributivo'
    ];

    /**
     * @param $regimen
     * @return mixed
     * @throws \Exception
     */
    public function getToken($regimen)
    {
        $regimen = strtolower($regimen);
        $token = '';
        switch ($regimen){
            case 'contributivo':
                $token = $this->token_contributivo;
                break;

            case 'subsidiado' :
                $token = $this->token_subsididado;
                break;

            default:
                throw new \Exception('El regimen no es valido');
        }

        return $token;
    }
}
