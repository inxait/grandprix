<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $this->call(RolesTableSeeder::class);
        $this->call(DistributorsTableSeeder::class);
        $this->call(AdminTableSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(MeasuresTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
    }

}
