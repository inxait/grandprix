<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;

class LoadSellersExcelTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function testItFailsOnEmptyNITExcel()
    {
        $excelfile = new UploadedFile(dirname(__DIR__).'/Unit/Fixtures/formato_nit_errors.xlsx', 'formato_nit_errors.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 6474, null, $test=true);
        $files = ['sellers_excel' => $excelfile];

        $response = $this->json('POST', 'users/upload', $files);

        $response->assertSessionHasErrors(['excel_errors']);
    }

    public function testItFailsOnEmptyCedulaExcel()
    {
        $excelfile = new UploadedFile(dirname(__DIR__).'/Unit/Fixtures/formato_cedula_errors.xlsx', 'formato_cedula_errors.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 6474, null, $test=true);
        $files = ['sellers_excel' => $excelfile];

        $response = $this->json('POST', 'users/upload', $files);

        $response->assertSessionHasErrors(['excel_errors']);
    }

    public function testItFailsOnEmptyCiudadExcel()
    {
        $excelfile = new UploadedFile(dirname(__DIR__).'/Unit/Fixtures/formato_ciudad_errors.xlsx', 'formato_ciudad_errors.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 6474, null, $test=true);
        $files = ['sellers_excel' => $excelfile];

        $response = $this->json('POST', 'users/upload', $files);

        $response->assertSessionHasErrors(['excel_errors']);
    }

    public function testItFailsOnEmptyGeneroExcel()
    {
        $excelfile = new UploadedFile(dirname(__DIR__).'/Unit/Fixtures/formato_genero_errors.xlsx', 'formato_genero_errors.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 6474, null, $test=true);
        $files = ['sellers_excel' => $excelfile];

        $response = $this->json('POST', 'users/upload', $files);

        $response->assertSessionHasErrors(['excel_errors']);
    }

    public function testItFailsOnErroneousGeneroExcel()
    {
        $excelfile = new UploadedFile(dirname(__DIR__).'/Unit/Fixtures/formato_genero_errors2.xlsx', 'formato_genero_errors2.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 6474, null, $test=true);
        $files = ['sellers_excel' => $excelfile];

        $response = $this->json('POST', 'users/upload', $files);

        $response->assertSessionHasErrors(['excel_errors']);
    }

    public function testItSavesDataOnSuccessExcel()
    {
        $excelfile = new UploadedFile(dirname(__DIR__).'/Unit/Fixtures/formato_asesores.xlsx', 'formato_asesores.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 6474, null, $test=true);
        $files = ['sellers_excel' => $excelfile];

        $response = $this->json('POST', 'users/upload', $files);

        $response->assertSessionHas(['status']);

        $users = User::all();

        $this->assertTrue(count($users) > 2);
    }
}
