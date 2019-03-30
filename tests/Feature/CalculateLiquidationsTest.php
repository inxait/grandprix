<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;

class CalculateLiquidationsTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();
        $this->artisan('db:seed');
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCanCreateFulfillmentPerUser()
    {
        //Upload sellers
        $excelfile = new UploadedFile(dirname(__DIR__).'/Unit/Fixtures/formato_asesores.xlsx', 'formato_asesores.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 6474, null, $test=true);
        $files = ['sellers_excel' => $excelfile];

        $response = $this->json('POST', 'users/upload', $files);
        $response->assertStatus(302)
                 ->assertSessionHas('status', 'Usuarios actualizados correctamente.');

        //Approve all
        $users = User::all();

        foreach ($users as $user) {
            $user->updated_data = true;
            $user->approved_sent = true;
            $user->save();
        }

        //Upload fulfillments
        $excelfile = new UploadedFile(dirname(__DIR__).'/Unit/Fixtures/formato_cumplimientos.xlsx', 'formato_cumplimientos.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 5535, null, $test=true);
        $files = ['fulfillments_excel' => $excelfile];
        $response = $this->json('POST', 'metrics/fulfillment/upload', $files);
        //echo json_encode($response->content());
        $response->assertStatus(302)
                 ->assertSessionHas('status', 'Cumplimientos actualizados correctamente.');

        //Upload sales
        $excelfile = new UploadedFile(dirname(__DIR__).'/Unit/Fixtures/formato_ventas.xlsx', 'formato_ventas.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9508, null, $test=true);
        $files = ['sales_excel' => $excelfile];
        $response = $this->json('POST', 'sales/upload', $files);
        $response->assertStatus(302)
                 ->assertSessionHas('status', 'Carga de ventas realizado correctamente.');

        //Create liquidation
        $liquidation = [
            'liquidation_name' => 'Liquidación lubricantes',
            'liquidation_percent' => 10,
            'measure_id' => 1, //lubricantes
        ];

        $response = $this->json('POST', 'liquidations/create', $liquidation);
        $response->assertStatus(302)
                 ->assertSessionHas('status', 'Se ha creado la liquidación correctamente');
    }
}
