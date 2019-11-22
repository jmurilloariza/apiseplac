<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Comentarios;
use App\Models\Evidencias;
use App\Models\PlanProyecto;
use App\Models\Seguimiento;
use App\Models\Plan;
use Illuminate\Http\Request;

class SeguimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->has('actividad_id') or !$request->has('periodo_evaluado') or !$request->has('fecha_seguimiento') or
            !$request->has('valoracion') or !$request->has('situacion_actual'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $actividad = Actividad::where(['actividad'])->exists();

        if (!$actividad)
            return response()->json([
                'message' => 'No existen registros de esa actividad',
                'data' => [],
                'status' => 'error'
            ], 200);

        $seguimiento = new Seguimiento([
            'actividad_id' => $request->get('actividad_id'),
            'periodo_evaluado' => $request->get('periodo_evaluado'),
            'fecha_seguimiento' => $request->get('fecha_seguimiento'),
            'valoracion' => $request->get('valoracion'),
            'situacion_actual' => $request->get('situacion_actual')
        ]);

        if (!$seguimiento->save())
            return response()->json([
                'message' => 'Ha ocurido un error',
                'data' => [],
                'status' => 'error'
            ], 200);

        return response()->json([
            'message' => 'Seguimiento establecido',
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
        $seguimiento = Seguimiento::where(['id' => $id])->with(['actividad', 'comentarios.evidencias'])->get()->toArray();

        if (count($seguimiento) > 0)
            return response()->json([
                'message' => 'Consulta exitosa',
                'data' => $seguimiento[0],
                'status' => 'ok'
            ], 200);

        return response()->json([
            'message' => 'No existen registros',
            'data' => [],
            'status' => 'error'
        ], 200);
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
        if (!$request->has('actividad_id') or !$request->has('periodo_evaluado') or !$request->has('fecha_seguimiento') or
            !$request->has('valoracion') or !$request->has('situacion_actual'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $seguimiento = Seguimiento::where(['id' => $id]);

        if (!$seguimiento->exists())
            return response()->json([
                'message' => 'No existen registros de ese seguimiento en la actividad',
                'data' => [],
                'status' => 'error'
            ], 200);

        $values = [
            'periodo_evaluado' => $request->get('periodo_evaluado'),
            'fecha_seguimiento' => $request->get('fecha_seguimiento'),
            'valoracion' => $request->get('valoracion'),
            'situacion_actual' => $request->get('situacion_actual')
        ];

        if ($seguimiento->update($values))
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $actividad_id
     * @return \Illuminate\Http\Response
     */
    public function showByActividad($actividad_id)
    {
        $seguimiento = Seguimiento::where(['actividad_id' => $actividad_id])->with(['actividad', 'comentarios.evidencias'])->get()->toArray();

        if (count($seguimiento) > 0)
            return response()->json([
                'message' => 'Consulta exitosa',
                'data' => $seguimiento[0],
                'status' => 'ok'
            ], 200);

        return response()->json([
            'message' => 'No existen registros',
            'data' => [],
            'status' => 'error'
        ], 200);
    }

    public function iniciarSeguimientoProyecto(Request $request)
    {
        if (!$request->has('plan_proyecto_id') or !$request->has('periodo_evaluado'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $planProyecto = PlanProyecto::where(['id' => $request->get('plan_proyecto_id')])
            ->with(['proyecto.actividades.seguimientos', 'plan']);

        if (!$planProyecto->exists())
            return response()->json([
                'message' => 'No existen registros',
                'data' => [],
                'status' => 'error'
            ], 200);

        $planProyecto = $planProyecto->get()->toArray()[0];
        $actividades  = $planProyecto['proyecto']['actividades'];

        for ($i = 0; $i < count($actividades); $i++) {
            $seguimientos = $actividades[$i]['seguimientos'];
            for ($j=0; $j < count($seguimientos); $j++) { 
                $seguimiento = $seguimientos[$j];
                if($seguimiento['fecha_seguimiento'] == null)
                    return response()->json([
                        'message' => 'No es posible iniciar el seguimiento porque hay un seguimiento vigente del periodo '.$seguimiento['periodo_evaluado'],
                        'data' => [],
                        'status' => 'error'
                    ], 200);
            }
        }

        for ($i = 0; $i < count($actividades); $i++) {
            $seguimiento = new Seguimiento([
                'actividad_id' => $actividades[$i]['id'],
                'periodo_evaluado' => $request->get('periodo_evaluado')
            ]);

            if (!$seguimiento->save())
                return response()->json([
                    'message' => 'Ha ocurido un error',
                    'data' => [],
                    'status' => 'error'
                ], 200);
        }

        return response()->json([
            'message' => 'Seguimiento para el periodo ' . $request->get('periodo_evaluado') . ' iniciado',
            'data' => [],
            'status' => 'ok'
        ], 201);
    }

    public function calcularPeriodosPendienteSeguimiento($plan_id)
    {
        $plan = Plan::where(['id' => $plan_id]);

        if (!$plan->exists())
            return response()->json([
                'message' => 'No existen registros de ese plan',
                'data' => [],
                'status' => 'error'
            ], 200);

        $plan = $plan->get()->toArray();

        $periodo_inicio = explode('-', $plan[0]['periodo_inicio']);
        $anioInicio = intval($periodo_inicio[0]);
        $semestreInicio =  $periodo_inicio[1];

        $periodo_fin = explode('-', $plan[0]['periodo_fin']);
        $anioFin = intval($periodo_fin[0]);
        $semestreFin = $periodo_fin[1];

        $periodos = [];
        $inicio = $anioInicio;
        $sinicio = $semestreInicio;
        $sfin = $semestreFin;

        while ($anioInicio <= $anioFin) {
            if ($anioInicio == $inicio && $sinicio == 'II') 
                array_push($periodos, $anioInicio . '-' . $semestreInicio);
            else {
                if ($anioInicio == $anioFin && $sfin == 'I')
                    array_push($periodos, $anioInicio . '-' . $sfin);
                else {
                    array_push($periodos, $anioInicio . '-' . $semestreInicio);
                    if ($semestreInicio == 'I') $semestreInicio = 'II';
                    else $semestreInicio = 'I';
                    array_push($periodos, $anioInicio . '-' . $semestreInicio);
                }
            }

            $anioInicio++;
        }

        $planesProyectos = PlanProyecto::where(['plan_id' => $plan_id])
            ->with(['proyecto.actividades.seguimientos', 'plan'])->get()->toArray();

        $p = [];

        for ($i = 0; $i < count($planesProyectos); $i++) {
            $actividades = $planesProyectos[$i]['proyecto']['actividades'];
            for ($j = 0; $j < count($actividades); $j++) {
                $seguimientos = $actividades[$j]['seguimientos'];
                foreach ($seguimientos as $seguimiento) {
                    for ($k = 0; $k < count($periodos); $k++) 
                        array_push($p, $seguimiento['periodo_evaluado']);
                }
            }
        }

        $periodos = array_diff($periodos, $p);

        return response()->json([
            'message' => 'Consulta exitosa',
            'data' => $periodos,
            'status' => 'ok'
        ], 200);
    }

    public function storeComentario(Request $request){
        if(!$request->has('seguimiento_id') or !$request->has('observacion'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $comentario = new Comentarios([
            'seguimiento_id' => $request->get('seguimiento_id'), 
            'observacion' => $request->get('observacion')
        ]);
        
        if (!$comentario->save())
            return response()->json([
                'message' => 'Ha ocurido un error',
                'data' => [],
                'status' => 'error'
            ], 200);

        if ($request->hasFile('evidencias')) {
            $evidencias = $request->file('evidencias');

            foreach ($evidencias as $file) {
                $time = time();
                $file->storeAs('public', $time . '-' . $file->getClientOriginalName());
                $url = 'storage/' . $time . '-' . $file->getClientOriginalName();

                $evidencia = new Evidencias([
                    'url' => $url, 
                    'comentario_id' => $comentario->id, 
                ]);

                if (!$evidencia->save())
                    return response()->json([
                        'message' => 'Ha ocurido un error',
                        'data' => [],
                        'status' => 'error'
                    ], 200);
            }
        }

        return response()->json([
            'message' => 'Comentario registrado',
            'data' => [$request->toArray()],
            'status' => 'ok'
        ], 201);
    }
}
