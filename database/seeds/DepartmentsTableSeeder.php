<?php

use App\Department;
use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Colombia
        Department::create(['name' => 'Amazonas']);
        Department::create(['name' => 'Antioquia']);
        Department::create(['name' => 'Arauca']);
        Department::create(['name' => 'Atlántico']);
        Department::create(['name' => 'Bogotá']);
        Department::create(['name' => 'Bolívar']);
        Department::create(['name' => 'Boyacá']);
        Department::create(['name' => 'Caldas']);
        Department::create(['name' => 'Caquetá']);
        Department::create(['name' => 'Casanare']);
        Department::create(['name' => 'Cauca']);
        Department::create(['name' => 'Cesar']);
        Department::create(['name' => 'Chocó']);
        Department::create(['name' => 'Córdoba']);
        Department::create(['name' => 'Cundinamarca']);
        Department::create(['name' => 'Guainía']);
        Department::create(['name' => 'Guaviare']);
        Department::create(['name' => 'Huila']);
        Department::create(['name' => 'La Guajira']);
        Department::create(['name' => 'Magdalena']);
        Department::create(['name' => 'Meta']);
        Department::create(['name' => 'Nariño']);
        Department::create(['name' => 'Norte de Santander']);
        Department::create(['name' => 'Putumayo']);
        Department::create(['name' => 'Quindío']);
        Department::create(['name' => 'Risaralda']);
        Department::create(['name' => 'San Andrés y Providencia']);
        Department::create(['name' => 'Santander']);
        Department::create(['name' => 'Sucre']);
        Department::create(['name' => 'Tolima']);
        Department::create(['name' => 'Valle del Cauca']);
        Department::create(['name' => 'Vaupés']);
        Department::create(['name' => 'Vichada']);
    }
}
