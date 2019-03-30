<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
  Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->hasAnyRole(['super-admin', 'admin'])) {
            return redirect('dashboard');
        } else {
            if(Auth::user()->hasAnyRole('seller')){
                return redirect('inicio');
            }else{
                return redirect('dealer/inicio');
            }
        }
    }

    return redirect('ingresar');
});

Route::get('ingresar', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('registro', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('recuperar-cuenta', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('departments/{id}/cities', 'CitiesController@getCitiesInDepartment');

Route::group(['middleware' => ['role:seller']], function () {
    Route::get('activarse-paso-1', 'UsersController@showActivateStep1');
    Route::post('account/update1', 'UsersController@updateProfileStep1');
    Route::get('activarse-paso-2', 'UsersController@showActivateStep2');
    Route::post('account/update2', 'UsersController@updateProfileStep2');

    Route::group(['middleware' => ['data.updated']], function () {
        Route::get('inicio', 'HomeController@index')->name('home');

        Route::get('que-es-grand-prix', function () {
            return view('pages.about');
        })->name('about');

        Route::get('como-gano-kms', function () {
            return view('pages.how-it-works');
        })->name('how-it-works');

        Route::get('trivias-grand-prix', 'TriviasController@showCurrentTrivia')->name('trivia-home');

        Route::get('trivias-inactivas', 'TriviasController@showNoTriviaActive');

        Route::get('premios-grand-prix', 'RewardsController@showMainRewards')->name('rewards');

        Route::get('perfil', 'UsersController@showProfile')->name('profile');

        Route::get('noticias', 'HomeController@showNews')->name('news');
        Route::get('noticias/{slug}', 'HomeController@showDetailNews')->name('news-detail');

        Route::get('active-trivia', 'TriviasController@getActiveTrivia');
        Route::post('evaluate-trivia', 'TriviasController@evaluateTrivia');

        Route::get('usuarios/mi-perfil', 'UsersController@showEditAccount');
        Route::post('users/sellers/update', 'UsersController@updateAccount')->name('update-seller');

        Route::get('period/{period}/ranking', 'HomeController@getRankingInPeriod');
        //Material
        Route::get('material', 'HomeController@getStudyMaterial')->name('material');
    });
});

Route::group(['middleware' => ['role:super-admin|admin']], function () {
    Route::group(['prefix' => 'dashboard'], function() {
        Route::get('/', 'DashboardController@index')->name('dashboard');
        Route::get('/exportar', 'DashboardController@export')->name('dashboard.export');
        Route::get('/descargar-reporte-general', 'DashboardController@downloadReport')->name('dashborad.download-report');

        //Usuarios registrados, no aprobados y no activos
        Route::get('/usuarios', 'UsersController@index')->name('dashboard.users');
        Route::get('/pendientes', 'UsersController@notApproved');
        Route::get('/inactivos', 'UsersController@notActivated');
        Route::get('/registrados', 'UsersController@usersRegistered');
        Route::post('/{id}/update-user/{tipo}', 'UsersController@updateUser');
        Route::get('/{id}/show-user/{tipo}', 'UsersController@showUpdateUser');

        Route::get('/metricas', 'MetricsController@showMetrics')->name('dashboard.metrics');
        Route::get('/metricas/cumplimientos/crear', 'MetricsController@showCreateFulfillment');

        Route::get('/categorias/crear', 'CategoriesController@showCreateCategory')->name('dashboard.create-category');
        Route::get('/noticias', 'PostsController@showNews')->name('dashboard.news');
        Route::get('/noticias/crear', 'PostsController@showCreateNews')->name('dashboard.create-news');
        Route::get('/noticias/{id}/editar', 'PostsController@edit')->name('dashboard.edit-news');

        Route::get('/cargar-ventas', 'SalesController@showLoadSales')->name('dashboard.load-sales');
        Route::get('/liquidaciones', 'LiquidationsController@index')->name('dashboard.liquidations');
        Route::get('/liquidaciones/crear', 'LiquidationsController@showCreate')->name('dashboard.create-liquidation');
        Route::get('/liquidaciones/{id}/calcular', 'LiquidationsController@calculateLiquidation');
        Route::get('/premios', 'RewardsController@index')->name('dashboard.rewards');
        Route::get('/premios/crear', 'RewardsController@showCreate')->name('dashboard.create-rewards');
        Route::get('/premios/{id}/editar', 'RewardsController@edit')->name('dashboard.edit-rewards');
        //Cambiar estado premios
        Route::get('/premios/estado/{id}','RewardsController@state');

        Route::resource('trivias', 'TriviasController');
        Route::get('trivias/{id}/participantes', 'TriviasExcelController@participationReport');
        Route::get('trivias/{id}/toggle', 'TriviasController@toggleTriviaState');

        Route::get('/configuraciones', 'SettingsController@index')->name('dashboard.settings');
        Route::get('/configuraciones/{id}', 'SettingsController@showSetting')->name('dashboard.setting-edit');

        Route::get('/puntos', 'PointsController@showUpdatePoints')->name('dashboard.update-points');
        Route::get('/informes', 'ReportsController@showCreateReports')->name('dashboard.list-reports');
        Route::get('/backups', 'BackupsController@showBackups')->name('dashboard.list-backups');
        Route::get('/backups/download/{name}', 'BackupsController@download')->name('dashboard.download-backup');
        Route::get('/backups/delete/{name}', 'BackupsController@delete')->name('dashboard.delete-backup');

        //Documentos
        Route::get('/material', 'DocumentsController@list')->name('documents.list');
        Route::get('/material/documentos/crear', 'DocumentsController@showCreate')->name('document.create');
        Route::post('/material/documentos/salvar', 'DocumentsController@store')->name('document.store');
        Route::get('/material/documentos/{id}/eliminar', 'DocumentsController@delete')->name('document.delete');
    });

    Route::get('admin/mi-perfil', 'UsersController@showEditAccount');
    Route::get('users/{id}/approve', 'UsersController@approveUserRegistration');
    Route::get('users/approve', 'UsersController@approveAllUsersRegistration');
    Route::get('users/not-active/download', 'ExcelUsersController@downloadNotActiveSellers');
    Route::get('users/active/download', 'ExcelUsersController@downloadRegisteredSellers');
    Route::get('users/{id}/delete', 'UsersController@delete');
    Route::get('rewards', 'RewardsController@list');
    Route::get('backups/create', 'BackupsController@create');

    Route::post('users/update', 'UsersController@updateAccount')->name('update-account');
    Route::post('users/upload', 'ExcelUsersController@uploadUsers')->name('upload-users');
    Route::post('sales/upload', 'SalesController@uploadSales')->name('upload-sales');
    Route::post('metrics/fulfillment/create', 'MetricsController@createFulfillment')->name('create-fulfillment');
    Route::post('metrics/fulfillment/upload', 'ExcelMetricsController@uploadFulfillment')->name('upload-fulfillment');
    Route::post('liquidations/create', 'LiquidationsController@store')->name('create-liquidation');
    Route::post('rewards/create', 'RewardsController@store')->name('create-reward');
    Route::put('rewards/{id}', 'RewardsController@update')->name('update-reward');
    Route::post('news/create', 'PostsController@createNews')->name('create-news');
    Route::put('news/{id}', 'PostsController@update')->name('update-news');
    Route::post('category/create', 'CategoriesController@store')->name('create-category');
    Route::post('setting/update', 'SettingsController@updateSetting')->name('update-setting');
    Route::post('reports/create', 'ReportsController@createReport')->name('create-report');
    Route::post('points/update', 'ExcelPointsController@uploadPoints')->name('update-points');
});

Route::group(['middleware' => ['role:dealer']], function () {
    Route::get('dealer/inicio', 'DealerController@index')->name('dealer-home');
    Route::get('dealer/download', 'DealerController@download');
    Route::get('dealer/report', 'DealerController@report');

    //Noticias
    Route::get('dealer/noticias', 'DealerController@showNews')->name('dealer-news');
    Route::get('dealer/noticias/{slug}', 'DealerController@showDetailNews')->name('dealer-news-detail');
    //Material de estudio
    Route::get('dealer/material', 'DealerController@getStudyMaterial')->name('material');
    //Detalle Puntos
    Route::get('dealer/points-user/{id}', 'DealerController@getPointsUser');

    Route::get('admin/mi-perfil', 'UsersController@showEditAccount');
    Route::post('users/sellers/update', 'UsersController@updateAccount')->name('update-seller');
});
