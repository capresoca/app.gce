<?php

use Illuminate\Database\Seeder;

class ConfigCacheSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        system('/*** Iniciando Limpieza de Cache ***/');
        system('php artisan cache:clear');
        system('php artisan route:clear');
        system('php artisan view:clear');
        system('php artisan config:cache');
        system('php artisan config:clear');
        system('/*** Finalización de limpieza de cache ***/');

    }
}
