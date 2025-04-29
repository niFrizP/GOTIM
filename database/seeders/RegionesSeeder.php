<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('regiones')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('regiones')->upsert([
            ['id_region' => 1, 'nombre_region' => 'Arica'],
            ['id_region' => 2, 'nombre_region' => 'Tarapacá'],
            ['id_region' => 3, 'nombre_region' => 'Antofagasta'],
            ['id_region' => 4, 'nombre_region' => 'Atacama'],
            ['id_region' => 5, 'nombre_region' => 'Coquimbo'],
            ['id_region' => 6, 'nombre_region' => 'Valparaíso'],
            ['id_region' => 7, 'nombre_region' => 'Metropolitana'],
            ['id_region' => 8, 'nombre_region' => 'O’Higgins'],
            ['id_region' => 9, 'nombre_region' => 'Maule'],
            ['id_region' => 10, 'nombre_region' => 'Ñuble'],
            ['id_region' => 11, 'nombre_region' => 'Biobío'],
            ['id_region' => 12, 'nombre_region' => 'La Araucanía'],
            ['id_region' => 13, 'nombre_region' => 'Los Ríos'],
            ['id_region' => 14, 'nombre_region' => 'Los Lagos'],
            ['id_region' => 15, 'nombre_region' => 'Aysén'],
            ['id_region' => 16, 'nombre_region' => 'Magallanes'],
        ], ['id_region'], ['nombre_region']);
    }
}
