<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlanProyecto;
use App\Models\ProgramaAcademico;
use App\Models\Proyecto;
use Illuminate\Http\Request;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class PlanController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
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
                'planesProyectos.proyecto.programas.programa.linea.eje', 
                'planesProyectos.proyecto.actividades'
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
                'data' => [ProgramaAcademico::where(['id' => $request->get('programa_academico_id')])->get()->toArray()],
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
            'planesProyectos.proyecto.programas.programa.linea.eje', 
            'planesProyectos.proyecto.actividades'
            ])->get()->toArray();

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

        if (!$request->has('periodo_inicio') or !$request->has('periodo_fin') or !$request->has('programa_academico_id') or !$request->has('nombre'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $values = [
            'periodo_inicio' => $request->get('periodo_inicio'),
            'periodo_fin' => $request->get('periodo_fin'),
            'programa_academico_id' => $request->get('programa_academico_id'),
            'nombre' => $request->get('nombre')
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

            for ($i=0, $long = count($actividades); $i < $long; $i++) { 
                $proyecto = new PlanProyecto([
                    'plan_id' => $request->get('plan_id'),
                    'actividades_id' => $actividades[$i]['id']
                ]);
    
                if (!$proyecto->save())
                    return response()->json([
                        'message' => 'Ha ocurido un error',
                        'data' => [],
                        'status' => 'error'
                    ], 200);
            }
        }

        return response()->json([
            'message' => 'Proyectos asociados al plan',
            'data' => [],
            'status' => 'ok'
        ], 201);
    }

    public function desasignarProyectosPlan($plan_proyecto){
        $planProyecto = PlanProyecto::where(['id' => $plan_proyecto])->with(['proyecto.actividades.seguimientos']);

        if(!$planProyecto->exists())
            return response()->json([
                'message' => 'Uno existe el proyecto relacionado al plan',
                'data' => [],
                'status' => 'error'
            ], 200);

        $actividades = $planProyecto->get()->toArray()[0]['proyecto']['actividades'];

        if(count($actividades) > 0){
            foreach($actividades as $actividad){
                if(count($actividad['seguimientos']) > 0)
                    return response()->json([
                        'message' => 'No es posible eliminar el proyecto ya que tiene actividades en seguimiento',
                        'data' => [],
                        'status' => 'error'
                    ], 200);
            }
        }

        if ($planProyecto->delete())
            return response()->json([
                'message' => 'Proyecto eliminado',
                'data' => [],
                'status' => 'ok'
            ], 200);
            
        return response()->json([
            'message' => 'Ocurrió un error',
            'data' => [],
            'status' => 'error'
        ], 200);
    }
    
}
