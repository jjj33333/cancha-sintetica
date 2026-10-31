<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Ejecuta el seeder base de la aplicación.
     *
     * Deja la base de datos lista para operar, sin datos de prueba.
     * Los seeders de demostración (InstalacionContactoSeeder y DatosPublicosSeeder)
     * se mantienen solo como referencia y pueden ejecutarse manualmente.
     */
    public function run(): void
    {
        $this->call([
            DatosBaseSeeder::class,
        ]);
    }
}
