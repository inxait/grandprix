<?php

use Illuminate\Database\Seeder;
use App\Distributor;

class DistributorsTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Distributor::create([
            'company_agile_id' => '5702666986455040',
            'name' => 'COMERCIALIZADORA REPUESTERA S.A.S',
            'nit' => '901003749',
        ]);
        Distributor::create([
            'company_agile_id' => '5675267779461120',
            'name' => 'IMPORTADORA NIPON',
            'nit' => '800095007-0',
        ]);
        Distributor::create([
            'company_agile_id' => '5664902681198592',
            'name' => 'MUNDIAL DE PARTES S.A.S.',
            'nit' => '890926650-1',
        ]);
        Distributor::create([
            'company_agile_id' => '5660980839186432',
            'name' => 'COLSAISA SAS',
            'nit' => '900338016',
        ]);
        Distributor::create([
            'company_agile_id' => '5742796208078848',
            'name' => 'COLMARA LTDA',
            'nit' => '9001477337',
        ]);
        Distributor::create([
            'company_agile_id' => '5750790484393984',
            'name' => 'JAMES MONTOYA Y/O AUTOCHEVROLET',
            'nit' => '16216035-4',
        ]);
        Distributor::create([
            'company_agile_id' => '5649050225344512',
            'name' => 'ALQUET DISTRIBUCIONES S.A.S.',
            'nit' => '901060369-7',
        ]);
        Distributor::create([
            'company_agile_id' => '5699257587728384',
            'name' => 'Abastecimientos Industriales S.A.S',
            'nit' => '890332891-1',
        ]);
    }

}
