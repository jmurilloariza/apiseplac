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

use Symfony\Component\HttpFoundation\Request;

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

    Route::group(['prefix' => 'proyecto'], function () {
        Route::get('{programa_academico}', 'PlanController@showByProgramaAcademico');
    });
});

Route::group(['prefix' => 'plan'], function () {
    Route::get('', 'PlanController@index');
    Route::post('', 'PlanController@store');
    Route::get('{plan}', 'PlanController@show');
    Route::post('{id}', 'PlanController@update');
    Route::delete('{id}', 'PlanController@destroy');

    Route::group(['prefix' => 'proyecto'], function () {
        Route::post('asignar', 'PlanController@asignarProyectosPlan');
        Route::delete('desasignar/{plan_proyecto}', 'PlanController@desasignarProyectosPlan');

        Route::group(['prefix' => 'seguimiento'], function () {
            Route::get('', 'SeguimientoController@index');
            Route::post('', 'SeguimientoController@store');
            Route::get('{id}', 'SeguimientoController@show');
            Route::put('{id}', 'SeguimientoController@update');
            Route::delete('{id}', 'SeguimientoController@destroy');
            
            Route::post('iniciar', 'SeguimientoController@iniciarSeguimientoProyecto');
            
            Route::get('actividad/{actividad_id}', 'SeguimientoController@showByActividad');
            Route::get('periodos/{plan_id}', 'SeguimientoController@calcularPeriodosPendienteSeguimiento');
            
            Route::group(['prefix' => 'comentario'], function () {
                Route::get('{seguimiento_id}', 'SeguimientoController@showComentarioBySeguimiento');
                Route::post('', 'SeguimientoController@storeComentario');
                
                Route::group(['prefix' => 'evidencia'], function () {
                    Route::post('', 'SeguimientoController@storeEvidencia');
                    Route::delete('{id}', 'SeguimientoController@destroyEvidencia');
                });
            });
        });
    });
});

Route::group(['prefix' => 'proyecto'], function () {
    Route::get('', 'ProyectoController@index');
    Route::get('{proyecto}', 'ProyectoController@show');
    Route::post('', 'ProyectoController@store');
    Route::put('{proyecto}', 'ProyectoController@update');
    Route::delete('{proyecto}', 'ProyectoController@destroy');
    Route::get('getProgramaAcademico/{programa_academico_id}', 'ProyectoController@showByPogramaAcademico');

    Route::group(['prefix' => 'actividad'], function () {
        Route::post('', 'ProyectoController@storeActividad');
        Route::get('{id}', 'ProyectoController@showActividad');
        Route::delete('{id}', 'ProyectoController@destroyActividad');
        Route::put('{id}', 'ProyectoController@updateActividad');

        Route::delete('recurso/{id}', 'ProyectoController@eliminarActividadRecurso');
        Route::delete('responsable/{id}', 'ProyectoController@eliminarUsuarioActividad');

        Route::post('recurso', 'ProyectoController@agregarRecursosActividad');
        Route::post('responsable', 'ProyectoController@agregarUsuarioActividad');
        
        Route::group(['prefix' => 'observacion'], function () {
            Route::get('{id}', 'ProyectoController@showObservationActividad');
        });
    });
});

Route::group(['prefix' => 'rol'], function () {
    Route::get('', 'UserController@getRoles');
    Route::get('{rol}', 'UserController@getUserRol');
});

Route::group(['prefix' => 'password'], function () {
    Route::post('', 'UserController@passwordReset');
    Route::post('change', 'UserController@passwordResetChange');
});
