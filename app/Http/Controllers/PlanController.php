<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlanActividad;
use App\Models\PlanProyecto;
use App\Models\ProgramaAcademico;
use App\Models\Proyecto;
use App\Models\Seguimiento;
use Illuminate\Http\Request;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co
 * @version 1.0
 */

class PlanController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'message' => 'Consulta exitosa',
            'data' => Plan::with([
                'programaAcademico',
                'planesActividades' => function($query) {$query->where(['estado' => 'ACTIVO']);},
                'planesActividades.actividad.proyecto.programas.programa.linea.eje',
                'planesActividades.actividad.proyecto.actividades'
                ])->get()->toArray(),
            'status' => 'ok'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->has('periodo_inicio') or !$request->has('periodo_fin') or
            !$request->has('programa_academico_id') or !$request->has('nombre'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        if (!$request->hasFile('documento'))
            return response()->json([
                'message' => 'Debe enviar el documento del plan',
                'data' => [],
                'status' => 'error'
            ], 200);

        $programaAcademico = ProgramaAcademico::where(['id' => $request->get('programa_academico_id')]);

        if (!$programaAcademico->exists())
            return response()->json([
                'message' => 'No existen registros del programa academico',
                'data' => [],
                'status' => 'error'
            ], 200);

        $planes = Plan::where(['programa_academico_id' => $request->get('programa_academico_id')])
            ->where(['fecha_cierre' => null]);

        if($planes->exists())
            return response()->json([
                'message' => 'No es posible crear el plan, existe un plan abierto y vigente',
                'data' => [],
                'status' => 'error'
            ], 200);

        $file = $request->file('documento');
        $time = time();
        $file->storeAs('public', $time . '-' . $file->getClientOriginalName());

        $plan = new Plan([
            'periodo_inicio' => $request->get('periodo_inicio'),
            'periodo_fin' => $request->get('periodo_fin'),
            'programa_academico_id' => $request->get('programa_academico_id'),
            'url_documento' => 'storage/' . $time . '-' . $file->getClientOriginalName(),
            'nombre' => $request->get('nombre')
        ]);

        if (!$plan->save())
            return response()->json([
                'message' => 'Ha ocurido un error',
                'data' => [],
                'status' => 'error'
            ], 200);

        return response()->json([
            'message' => 'Plan creado',
            'data' => [],
            'status' => 'ok'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $plan = Plan::where(['id' => $id])->with([
            'programaAcademico',
            'planesActividades.actividad.indicador',
            'planesActividades.actividad.proyecto.programas.programa.linea.eje'
        ])->get()->toArray()[0];

        $planesActividades = $plan['planes_actividades'];
        $proyectos = [];

        for ($i = 0, $long = count($planesActividades); $i < $long; $i++) {
            $actividad = $planesActividades[$i]['actividad'];
            $proyecto = $actividad['proyecto'];
            $encontro = false;

            $actividad_data = [
                'nombre' => $actividad['nombre'],
                'descripcion' => $actividad['descripcion'],
                'fecha_inicio' => $planesActividades[$i]['fecha_inicio'],
                'fecha_fin' => $planesActividades[$i]['fecha_inicio'],
                'costo' => $planesActividades[$i]['costo'],
                'unidad_medida' => $actividad['descripcion'],
                'peso' => $planesActividades[$i]['peso'],
                'incidacor' => $actividad['indicador']
            ];

            for ($j=0; $j < count($proyectos); $j++) {
                $encontro = $proyectos[$j]['id'] == $proyecto['id'];

                if($proyectos[$j]['id'] == $proyecto['id']){
                    array_push($proyectos[$j]['actividades'], $actividad_data);
                    break;
                }
            }

            if(!$encontro){
                $proyecto['actividades'] = [$actividad_data];
                array_push($proyectos, $proyecto);
            }
        }

        $data = [];
        $data['nombre'] = $plan['nombre'];
        $data['url_documento'] = $plan['url_documento'];
        $data['periodo_inicio'] = $plan['periodo_inicio'];
        $data['periodo_fin'] = $plan['periodo_fin'];
        $data['fecha_cierre'] = $plan['fecha_cierre'];
        $data['programa_academico'] = $plan['programa_academico'];
        $data['proyectos'] = $proyectos;

        if (count($plan) > 0)
            return response()->json([
                'message' => 'Consulta exitosa',
                'data' => $data,
                'status' => 'ok'
            ], 200);

        return response()->json([
            'message' => 'No existen registros',
            'data' => [],
            'status' => 'error'
        ], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $programa_academico
     * @return \Illuminate\Http\Response
     */
    public function showByProgramaAcademico($programa_academico)
    {
        $plan = Plan::where(['programa_academico_id' => $programa_academico])->with(['programaAcademico', 'proyectos.programas.linea.eje', 'proyectos.actividades'])
            ->get()->toArray();

        if (count($plan) > 0)
            return response()->json([
                'message' => 'Consulta exitosa',
                'data' => $plan[0],
                'status' => 'ok'
            ], 200);

        return response()->json([
            'message' => 'No existen registros',
            'data' => [],
            'status' => 'error'
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $plan = Plan::where(['id' => $id]);

        if (!$plan->exists())
            return response()->json([
                'message' => 'No existen registros de ese plan',
                'data' => [],
                'status' => 'error'
            ], 200);

        if (!$request->has('periodo_inicio') or !$request->has('periodo_fin') or
            !$request->has('programa_academico_id') or !$request->has('nombre') or !$request->has('fecha_cierre') )
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $values = [
            'periodo_inicio' => $request->get('periodo_inicio'),
            'periodo_fin' => $request->get('periodo_fin'),
            'programa_academico_id' => $request->get('programa_academico_id'),
            'nombre' => $request->get('nombre'),
            'fecha_cierre' => $request->get('fecha_cierre')
        ];

        if ($request->hasFile('documento')) {
            $file = $request->file('documento');
            $time = time();
            $file->storeAs('public', $time . '-' . $file->getClientOriginalName());
            $values['url_documento'] = 'storage/' . $time . '-' . $file->getClientOriginalName();
        }

        if ($plan->update($values))
            return response()->json([
                'message' => 'Actualización exitosa',
                'data' => [],
                'status' => 'ok'
            ], 200);

        return response()->json([
            'message' => 'Ha ocurido un error',
            'data' => [],
            'status' => 'error'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $plan = Plan::where(['id' => $id]);
        $relaciones = $plan->with(['planesProyectos'])->get()->toArray()[0];

        if (count($relaciones['planes_proyectos']) > 0)
            return response()->json([
                'message' => 'El plan tiene proyectos asignados y es posible que esten en seguimiento',
                'data' => [],
                'status' => 'error'
            ], 200);

        if ($plan->delete())
            return response()->json([
                'message' => 'Plan eliminado',
                'data' => [],
                'status' => 'ok'
            ], 200);

        return response()->json([
            'message' => 'Ocurrió un error',
            'data' => [],
            'status' => 'error'
        ], 200);
    }

    public function asignarProyectosPlan(Request $request)
    {
        if (!$request->has('plan_id') or !$request->has('proyectos'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $plan = Plan::where(['id' => $request->get('plan_id')]);

        if (!$plan->exists())
            return response()->json([
                'message' => 'No existen registros de ese plan',
                'data' => [],
                'status' => 'error'
            ], 200);

        $proyectos = $request->get('proyectos');
        $plan = $plan->get()->toArray()[0];

        foreach ($proyectos as $pr) {
            $proyecto = Proyecto::where(['id' => $pr]);

            if (!$proyecto->exists())
                return response()->json([
                    'message' => 'No existen registros de un plan con el identificador ' . $pr,
                    'data' => [],
                    'status' => 'error'
                ], 200);

            $proyecto = $proyecto->with(['actividades'])->get()->toArray()[0];
            $actividades = $proyecto['actividades'];

            if(count($actividades) == 0)
                return response()->json([
                    'message' => 'El proyecto no tiene actividades registradas',
                    'data' => [],
                    'status' => 'error'
                ], 200);

            for ($i=0, $long = count($actividades); $i < $long; $i++) {
                $proyecto = new PlanActividad([
                    'plan_id' => $request->get('plan_id'),
                    'actividades_id' => $actividades[$i]['id'],
                    'fecha_inicio' => $actividades[$i]['fecha_inicio'],
                    'fecha_fin' => $actividades[$i]['fecha_fin'],
                    'costo' => $actividades[$i]['costo'],
                    'peso' => $actividades[$i]['peso'],
                    'estado' => 'ACTIVO'
                ]);

                if (!$proyecto->save())
                    return response()->json([
                        'message' => 'Ha ocurido un error',
                        'data' => [],
                        'status' => 'error'
                    ], 200);

                for ($j=intval($plan['periodo_inicio']), $long2 = intval($plan['periodo_fin']); $j <= $long2; $j++) {
                    $seguimiento = new Seguimiento([
                        'plan_actividad_id' => $proyecto->id,
                        'periodo_evaluado' => $j.'',
                        'fecha_seguimiento' => null,
                        'valoracion' => 0,
                        'situacion_actual' => 'Bajo',
                        'estado' => 'ACTIVO', 
                        'avance' => 1
                    ]);

                    $seguimiento->save();
                }

            }
        }

        return response()->json([
            'message' => 'Proyectos asociados al plan',
            'data' => [intval($plan['periodo_inicio']), intval($plan['periodo_fin'])],
            'status' => 'ok'
        ], 201);
    }

    public function desasignarProyectosPlan($plan_id, $proyecto_id){
        $proyecto = Proyecto::where(['id' => $proyecto_id]);

        if(!$proyecto->exists())
            return response()->json([
                'message' => 'Uno existe el proyecto relacionado al plan',
                'data' => [],
                'status' => 'error'
            ], 200);

        $proyecto = $proyecto->with(['actividades'])->get()->toArray()[0];
        $actividades = $proyecto['actividades'];

        foreach ($actividades as $actividad) {
            $planActividad = PlanActividad::where(['plan_id' => $plan_id, 'actividad_id' => $actividad['id']])
                ->with(['seguimientos.comentarios.evidencias'])->delete();
        }

        return response()->json([
            'message' => 'Proyecto desasignado',
            'data' => [],
            'status' => 'ok'
        ], 200);

    }

}
