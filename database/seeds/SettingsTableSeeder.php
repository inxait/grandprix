<?php

use App\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'type' => 1,
            'key' => 'link-facebook',
            'title' => 'URL Facebook',
            'value' => null,
        ]);

        Setting::create([
            'type' => 1,
            'key' => 'link-twitter',
            'title' => 'URL Twitter',
            'value' => null,
        ]);

        Setting::create([
            'type' => 2,
            'key' => 'google-analitycs',
            'title' => 'Tracking id Google Analytics',
            'value' => null,
        ]);

        Setting::create([
            'type' => 2,
            'key' => 'valor-unidad-lubricantes',
            'title' => 'Valor x unidad lubricantes',
            'value' => 40000,
        ]);

        Setting::create([
            'type' => 2,
            'key' => 'valor-unidad-baterias',
            'title' => 'Valor x unidad baterías',
            'value' => 200000,
        ]);
    }
}
