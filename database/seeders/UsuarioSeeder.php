<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::create([
            'nombre'   => 'Administrador',
            'rol'      => 'administrador',
            'usuario'  => 'admin',
            'password' => Hash::make('admin123'),
            'activo'   => true,
        ]);

        Usuario::create([
            'nombre'   => 'Cajero 1',
            'rol'      => 'cajero',
            'usuario'  => 'cajero1',
            'password' => Hash::make('cajero123'),
            'activo'   => true,
        ]);

        Usuario::create([
            'nombre'   => 'Cocina 1',
            'rol'      => 'cocina',
            'usuario'  => 'cocina1',
            'password' => Hash::make('cocina123'),
            'activo'   => true,
        ]);
    }
}