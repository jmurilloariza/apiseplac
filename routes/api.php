<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Models\Eje;

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::get('error', function () {
        return response()->json(['error' => 'Unauthorized', 'message' => 'Token invalido', 'status' => 'error'], 401);
    })->name('error');
});

Route::apiResources([
    'eje' => 'EjeController',
    'linea' => 'LineaController',
    'programa' => 'ProgramaController',
    'facultad' => 'FacultadController',
    'users' => 'UserController',
    'recurso' => 'RecursoController',
    'indicador' => 'IndicadorController'
]);

Route::group(['prefix' => 'departamento'], function () {
    Route::get('', 'FacultadController@indexDepartamento');
    Route::post('', 'FacultadController@storeDepartamento');
    Route::put('{departamento}', 'FacultadController@updateDepartamento');
    Route::get('{departamento}', 'FacultadController@showDepartamento');
    Route::delete('{departamento}', 'FacultadController@destroyDepartamento');
});

Route::group(['prefix' => 'programa_academico'], function () {
    Route::get('', 'FacultadController@indexProgramaAcademico');
    Route::post('', 'FacultadController@storeProgramaAcademico');
    Route::put('{programa_academico}', 'FacultadController@updateProgramaAcademico');
    Route::get('{programa_academico}', 'FacultadController@showProgramaAcademico');
    Route::delete('{programa_academico}', 'FacultadController@destroyProgramaAcademico');
});

Route::group(['prefix' => 'plan'], function () {
    Route::get('', 'PlanController@index');
    Route::post('', 'PlanController@store');
    Route::get('{plan}', 'PlanController@show');

    Route::group(['prefix' => 'proyecto'], function () {
        Route::get('', 'PlanController@indexProyecto');
        Route::get('{proyecto}', 'PlanController@showProyecto');
        Route::post('', 'PlanController@storeProyecto');
        Route::put('{proyecto}', 'PlanController@updateProyecto');
        Route::delete('{proyecto}', 'PlanController@destroyProyecto');
    });
});

Route::group(['prefix' => 'rol'], function () {
    Route::get('', 'UserController@getRoles');
});

Route::get('p', function(){
    return Eje::find(1)->delete().'';
});
