<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usuarios base
        $usuarios = [
            [
                'nombre' => 'Admin',
                'apellido' => 'General',
                'email' => 'admin@gotim.cl',
                'password' => Hash::make('admin123'),
                'rol' => 'Administrador',
            ],
            [
                'nombre' => 'Carlos',
                'apellido' => 'Jefe',
                'email' => 'supervisor@gotim.cl',
                'password' => Hash::make('supervisor123'),
                'rol' => 'Supervisor',
            ],
            [
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'email' => 'tecnico1@gotim.cl',
                'password' => Hash::make('tecnico123'),
                'rol' => 'Técnico',
            ],
        ];

        foreach ($usuarios as $usuario) {
            User::create(array_merge($usuario, [
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]));
        }

        // Seed regiones y ciudades
        $this->call([
            RegionesSeeder::class,
            CiudadesSeeder::class,
            EstadoOTSeeder::class,
        ]);
    }
}
