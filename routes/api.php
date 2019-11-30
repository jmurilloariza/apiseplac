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

use App\Models\Plan;
use App\Models\Usuario;

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
                Route::delete('{id}', 'SeguimientoController@destroyComentario');
                Route::put('{id}', 'SeguimientoController@updateComentario');

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

        Route::group(['prefix' => 'recurso'], function () {
            Route::post('', 'ProyectoController@agregarRecursosActividad');
            Route::delete('{id}', 'ProyectoController@eliminarActividadRecurso');
        });

        Route::group(['prefix' => 'responsable'], function () {
            Route::delete('{id}', 'ProyectoController@eliminarUsuarioActividad');
            Route::post('', 'ProyectoController@agregarUsuarioActividad');
            Route::get('{usaurio_id}', 'ProyectoController@showActividadByUsuario');
        });

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
    Route::put('{id}', 'UserController@passwordChange');
    Route::post('change', 'UserController@passwordResetChange');
});

Route::get('z', function(){
    $plan = Plan::where(['id' => 3])->with([
        'programaAcademico',
        'planesProyectos.proyecto.actividades.seguimientos',
        'planesProyectos.proyecto.programas.programa.linea.eje',
    ])->get()->toArray()[0];

    $periodoEvaluado = '2019-I';

    $data = [];
    $director = Usuario::where(['programa_academico_id' => $plan['programa_academico']['id'], 'rol_id' => 4])->get()->toArray()[0];

    $nombreDirector = $director['name'] ?? '';
    $nombreDirector .= ' ' . $director['apellidos'] ?? '';

    $data['plan']['programa_academico'] = $plan['programa_academico']['nombre'];
    $data['plan']['director'] = $nombreDirector;
    $data['plan']['nombre'] = $plan['nombre'];
    $data['plan']['vigencia'] = $plan['periodo_inicio'] . ' al ' . $plan['periodo_fin'];
    $data['plan']['periodo_evaluado'] = $periodoEvaluado;

    $data['plan']['proyectos'] = [];

    $planesProyectos = $plan['planes_proyectos'];

    foreach ($planesProyectos as $planProyecto) {
        $proyecto = $planProyecto['proyecto'];
        $data_proyecto = [
            'nombre' => $proyecto['nombre'],
            'descripcion' => $proyecto['descripcion'],
        ];

        $data_proyecto['programas'] = [];
        $programas = $proyecto['programas'];

        foreach ($programas as $p) {
            $programa = $p['programa'];
            array_push($data_proyecto['programas'], $programa['nombre']);
            $data_proyecto['eje'] = $programa['linea']['eje']['nombre'];
            $data_proyecto['linea'] = $programa['linea']['nombre'];
        }

        $actividades = $proyecto['actividades'];
        $data_proyecto['procentaje_avance'] = 0;

        foreach ($actividades as $actividad) {
            $seguimientos = $actividad['seguimientos'];

            foreach ($seguimientos as $seguimiento) {
                if ($seguimiento['periodo_evaluado'] == $periodoEvaluado) {
                    $data_proyecto['procentaje_avance'] += intval($actividad['peso']) * intval($seguimiento['valoracion']);
                    break;
                }
            }
        }

        $data_proyecto['procentaje_avance'] = $data_proyecto['procentaje_avance'] / 100;

        array_push($data['plan']['proyectos'], $data_proyecto);
    }
    
    return view('reports.resumenPlan')->with(['plan' => $data['plan']]);
});

Route::group(['prefix' => 'reportes'], function () {
    Route::get('resumenPlanPeriodoPrograma', 'ReportesController@resumenPlanPeriodoPrograma');
    Route::get('x', 'ReportesController@resumenPlanPeriodoProgramaRender');
});
