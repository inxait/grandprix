<?php

use App\Measure;
use Illuminate\Database\Seeder;

class MeasuresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Measure::create([
            'name' => 'Lubricantes',
            'units' => 'galones',
            'type' => 'puntos',
        ]);

        Measure::create([
            'name' => 'Baterías',
            'units' => 'unidades',
            'type' => 'puntos',
        ]);

        Measure::create([
            'name' => 'Autopartes',
            'units' => 'pesos',
            'type' => 'puntos',
        ]);
    }
}
