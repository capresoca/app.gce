<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 13/02/2019
 * Time: 3:43 PM
 */

namespace App\Jobs\Mipres;


use App\Models\Mipres\MpConfig;
use GuzzleHttp\Client;

class MipresClient extends Client
{
    public function __construct()
    {
        $config = MpConfig::first();

        $config = [
            'base_uri' => $config->dominio,
            'timeout'  => 600,
            'verify'   => false
        ];

        parent::__construct($config);
    }

}