<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadoOT;

class EstadoOTSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            ['nombre_estado' => 'Recepcionada', 'descripcion' => 'Orden recibida por el sistema.'],
            ['nombre_estado' => 'En Evaluación', 'descripcion' => 'Orden está siendo evaluada.'],
            ['nombre_estado' => 'Informe Técnico', 'descripcion' => 'Se generó el informe técnico.'],
            ['nombre_estado' => 'Detalle de Reparación', 'descripcion' => 'Se agregó el detalle de reparación.'],
            ['nombre_estado' => 'Fin Evaluación', 'descripcion' => 'Etapa de evaluación finalizada.'],
            ['nombre_estado' => 'Inicio Reparación', 'descripcion' => 'Comienza el proceso de reparación.'],
            ['nombre_estado' => 'Fin Reparación', 'descripcion' => 'Reparación completada.'],
            ['nombre_estado' => 'Finalizada', 'descripcion' => 'Orden de trabajo finalizada.'],
        ];

        foreach ($estados as $estado) {
            EstadoOT::firstOrCreate(['nombre_estado' => $estado['nombre_estado']], $estado);
        }
    }
}
