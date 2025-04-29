<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CiudadesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ciudades')->delete();
        DB::table('ciudades')->insert([
            // Región de Arica y Parinacota
            ['nombre_ciudad' => 'Arica', 'id_region' => 1],
            ['nombre_ciudad' => 'Putre', 'id_region' => 1],
            ['nombre_ciudad' => 'Camarones', 'id_region' => 1],
            // Región de Tarapacá
            ['nombre_ciudad' => 'Iquique', 'id_region' => 2],
            ['nombre_ciudad' => 'Alto Hospicio', 'id_region' => 2],
            ['nombre_ciudad' => 'Pica', 'id_region' => 2],
            // Región de Antofagasta
            ['nombre_ciudad' => 'Antofagasta', 'id_region' => 3],
            ['nombre_ciudad' => 'Calama', 'id_region' => 3],
            ['nombre_ciudad' => 'Tocopilla', 'id_region' => 3],
            // Región de Atacama
            ['nombre_ciudad' => 'Copiapó', 'id_region' => 4],
            ['nombre_ciudad' => 'Vallenar', 'id_region' => 4],
            ['nombre_ciudad' => 'Chañaral', 'id_region' => 4],
            // Región de Coquimbo
            ['nombre_ciudad' => 'La Serena', 'id_region' => 5],
            ['nombre_ciudad' => 'Coquimbo', 'id_region' => 5],
            ['nombre_ciudad' => 'Ovalle', 'id_region' => 5],
            // Región de Valparaíso
            ['nombre_ciudad' => 'Valparaíso', 'id_region' => 6],
            ['nombre_ciudad' => 'Viña del Mar', 'id_region' => 6],
            ['nombre_ciudad' => 'Quilpué', 'id_region' => 6],
            // Región Metropolitana
            ['nombre_ciudad' => 'Santiago', 'id_region' => 7],
            ['nombre_ciudad' => 'Puente Alto', 'id_region' => 7],
            ['nombre_ciudad' => 'Maipú', 'id_region' => 7],
            ['nombre_ciudad' => 'Las Condes', 'id_region' => 7],
            ['nombre_ciudad' => 'La Florida', 'id_region' => 7],
            ['nombre_ciudad' => 'San Bernardo', 'id_region' => 7],
            ['nombre_ciudad' => 'Pudahuel', 'id_region' => 7],
            ['nombre_ciudad' => 'Quilicura', 'id_region' => 7],
            ['nombre_ciudad' => 'Renca', 'id_region' => 7],
            ['nombre_ciudad' => 'Cerro Navia', 'id_region' => 7],
            ['nombre_ciudad' => 'Estación Central', 'id_region' => 7],
            ['nombre_ciudad' => 'La Granja', 'id_region' => 7],
            ['nombre_ciudad' => 'Peñalolén', 'id_region' => 7],
            ['nombre_ciudad' => 'San Miguel', 'id_region' => 7],
            ['nombre_ciudad' => 'La Cisterna', 'id_region' => 7],
            ['nombre_ciudad' => 'El Bosque', 'id_region' => 7],
            ['nombre_ciudad' => 'San Joaquín', 'id_region' => 7],
            ['nombre_ciudad' => 'Macul', 'id_region' => 7],
            ['nombre_ciudad' => 'Ñuñoa', 'id_region' => 7],
            ['nombre_ciudad' => 'Providencia', 'id_region' => 6],
            // Región de O'Higgins
            ['nombre_ciudad' => 'Rancagua', 'id_region' => 8],
            ['nombre_ciudad' => 'Machalí', 'id_region' => 8],
            ['nombre_ciudad' => 'San Fernando', 'id_region' => 8],
            // Región del Maule
            ['nombre_ciudad' => 'Talca', 'id_region' => 9],
            ['nombre_ciudad' => 'Linares', 'id_region' => 9],
            ['nombre_ciudad' => 'Maule', 'id_region' => 9],
            ['nombre_ciudad' => 'Cauquenes', 'id_region' => 9],
            ['nombre_ciudad' => 'Colbún', 'id_region' => 9],
            ['nombre_ciudad' => 'Constitución', 'id_region' => 9],
            ['nombre_ciudad' => 'Curicó', 'id_region' => 9],
            ['nombre_ciudad' => 'Linares', 'id_region' => 9],
            // Región de Ñuble
            ['nombre_ciudad' => 'Chillán', 'id_region' => 10],
            ['nombre_ciudad' => 'San Carlos', 'id_region' => 10],
            ['nombre_ciudad' => 'Quirihue', 'id_region' => 10],
            // Región del Biobío
            ['nombre_ciudad' => 'Concepción', 'id_region' => 11],
            ['nombre_ciudad' => 'Talcahuano', 'id_region' => 11],
            ['nombre_ciudad' => 'Los Ángeles', 'id_region' => 11],
            ['nombre_ciudad' => 'Coronel', 'id_region' => 11],
            ['nombre_ciudad' => 'San Pedro de la Paz', 'id_region' => 11],
            ['nombre_ciudad' => 'Chiguayante', 'id_region' => 11],
            ['nombre_ciudad' => 'Penco', 'id_region' => 11],
            ['nombre_ciudad' => 'Hualpén', 'id_region' => 11],
            ['nombre_ciudad' => 'Lota', 'id_region' => 11],
            ['nombre_ciudad' => 'Tirúa', 'id_region' => 11],
            // Región de La Araucanía
            ['nombre_ciudad' => 'Temuco', 'id_region' => 12],
            ['nombre_ciudad' => 'Pucón', 'id_region' => 12],
            ['nombre_ciudad' => 'Villarrica', 'id_region' => 12],
            // Región de Los Ríos
            ['nombre_ciudad' => 'Valdivia', 'id_region' => 13],
            ['nombre_ciudad' => 'La Unión', 'id_region' => 13],
            ['nombre_ciudad' => 'Río Bueno', 'id_region' => 13],
            // Región de Los Lagos
            ['nombre_ciudad' => 'Puerto Montt', 'id_region' => 14],
            ['nombre_ciudad' => 'Osorno', 'id_region' => 14],
            ['nombre_ciudad' => 'Puerto Varas', 'id_region' => 14],
            // Región de Aysén
            ['nombre_ciudad' => 'Coyhaique', 'id_region' => 15],
            ['nombre_ciudad' => 'Puerto Aysén', 'id_region' => 15],
            ['nombre_ciudad' => 'Chile Chico', 'id_region' => 15],
            // Región de Magallanes y de la Antártica Chilena
            ['nombre_ciudad' => 'Punta Arenas', 'id_region' => 16],
            ['nombre_ciudad' => 'Puerto Natales', 'id_region' => 16],
            ['nombre_ciudad' => 'Porvenir', 'id_region' => 16],
        ]);
    }
}
