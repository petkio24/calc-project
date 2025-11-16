<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            TurningMaterialsSeeder::class,
            ToolMaterialsSeeder::class,
            ToolGeometriesSeeder::class,
            MachineTypesSeeder::class,
            OperationFactorsSeeder::class,
            AdditionalMaterialsSeeder::class,
            UpdateMachineTypesSeeder::class,
            UpdateOperationFactorsSeeder::class,
            DrillingMaterialsSeeder::class,
            DrillingToolsSeeder::class,
        ]);
    }
}
