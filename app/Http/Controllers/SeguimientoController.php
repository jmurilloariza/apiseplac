<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Comentarios;
use App\Models\Evidencias;
use App\Models\PlanProyecto;
use App\Models\Seguimiento;
use App\Models\Plan;
use App\Models\Usuario;
use Illuminate\Http\Request;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 2.0
 */

class SeguimientoController extends Controller
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
            'valoracion' => 0,
            'situacion_actual' => 'Bajo'
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
        $seguimiento = Seguimiento::where(['id' => $id])->with(['actividad', 'comentarios.evidencias.autor'])->get()->toArray();

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
            'valoracion' => intval($request->get('valoracion')),
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

        return response()->json([
            'message' => 'Consulta exitosa',
            'data' => $seguimiento,
            'status' => 'ok'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
                'periodo_evaluado' => $request->get('periodo_evaluado'), 
                'valoracion' => 0, 
                'situacion_actual' => 'Bajo'
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function terminarSeguimientoProyecto(Request $request)
    {
        if(!$request->has('proyecto_plan_id') or !$request->has('periodo'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $proyecto = PlanProyecto::where(['id' => $request->get('proyecto_plan_id')]);

        if(!$proyecto->exists())
            return response()->json([
                'message' => 'No existen registros del proyecto',
                'data' => [$request->get('proyecto_plan_id')],
                'status' => 'error'
            ], 200);

        $proyecto = $proyecto->with(['proyecto.actividades.seguimientos'])
            ->get()->toArray()[0]['proyecto'];

        $actividades = $proyecto['actividades'];

        foreach ($actividades as $actividad) {
            $seguimientos = $actividad['seguimientos'];
            
            foreach ($seguimientos as $item) {
                if($item['periodo_evaluado'] == $request->get('periodo'))
                Seguimiento::where(['id' => $item['id']])->update(['fecha_seguimiento' => date('Y-m-d')]);
            }
        }

        return response()->json([
            'message' => 'Seguimientos terminados para el periodo '.$request->get('periodo'),
            'data' => [],
            'status' => 'ok'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $plan_id
     * @return \Illuminate\Http\Response
     */
    public function calcularPeriodosPendienteSeguimiento($proyecto_plan_id)
    {
        $planesProyectos = PlanProyecto::where(['id' => $proyecto_plan_id])
            ->with(['proyecto.actividades.seguimientos', 'plan'])->get()->toArray()[0];

        $plan = $planesProyectos['plan'];
        $periodos = $this->calcularTodosPeriodosPlan($plan['id']);

        
        $data = [];
        $actividades = $planesProyectos['proyecto']['actividades'];

        for ($i=0, $long = count($periodos); $i < $long; $i++) { 
            $valoracion = 0;
            $fecha_seguimiento = '';

            for ($j = 0; $j < count($actividades); $j++) {
                $seguimientos = $actividades[$j]['seguimientos'];
                
                foreach ($seguimientos as $seguimiento) {
                    if($seguimiento['periodo_evaluado'] == $periodos[$i]){
                        $valoracion += $seguimiento['valoracion'] * $actividades[$j]['peso'];
                        $fecha_seguimiento = $seguimiento['fecha_seguimiento'];
                        break;
                    }
                }
            }

            array_push($data, [
                'periodo' => $periodos[$i], 
                'fecha_seguimiento' => $fecha_seguimiento, 
                'valoracion' => $valoracion > 0 ? $valoracion/100: 0
            ]);
        }

        return response()->json([
            'message' => 'Consulta exitosa',
            'data' => $data,
            'status' => 'ok'
        ], 200);
    }

    public function obtenerPeriodosPlan($plan_id){
        $periodos = $this->calcularTodosPeriodosPlan($plan_id);
        return response()->json([
            'message' => 'Consulta exitosa', 
            'data' => $periodos, 
            'status' => 'ok'
        ], 200);
    }

    private function calcularTodosPeriodosPlan($plan_id){
        $plan = Plan::where(['id' => $plan_id])->get()->toArray()[0];

        $periodo_inicio = explode('-', $plan['periodo_inicio']);
        $anioInicio = intval($periodo_inicio[0]);
        $semestreInicio =  $periodo_inicio[1];

        $periodo_fin = explode('-', $plan['periodo_fin']);
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

        return $periodos;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeComentario(Request $request){
        if(!$request->has('seguimiento_id') or !$request->has('comentario') or !$request->has('autor_id'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $seguimiento = Seguimiento::where(['id' => $request->get('seguimiento_id')])->exists();

        if(!$seguimiento)
            return response()->json([
                'message' => 'No existen registros de ese seguimiento',
                'data' => [],
                'status' => 'error'
            ], 200);

        $autor = Usuario::where(['id' => $request->get('autor_id')])->exists();

        if(!$autor)
            return response()->json([
                'message' => 'No existen registros de ese usuario',
                'data' => [],
                'status' => 'error'
            ], 200);

        $comentario = new Comentarios([
            'seguimiento_id' => $request->get('seguimiento_id'), 
            'observacion' => $request->get('comentario'), 
            'autor_id' => $request->get('autor_id')
        ]);
        
        if (!$comentario->save())
            return response()->json([
                'message' => 'Ha ocurido un error',
                'data' => [],
                'status' => 'error'
            ], 200);


        return response()->json([
            'message' => 'Comentario registrado',
            'data' => $comentario->id,
            'status' => 'ok'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showComentario($id)
    {
        $comentario = Comentarios::where(['id' => $id])->with(['seguimiento', 'evidencias', 'autor'])->get()->toArray();

        if (count($comentario) > 0)
            return response()->json([
                'message' => 'Consulta exitosa',
                'data' => $comentario[0],
                'status' => 'ok'
            ], 200);

        return response()->json([
            'message' => 'No existen registros',
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
    public function destroyComentario($id)
    {
        $comentario = Comentarios::where(['id' => $id]);

        if(!$comentario->exists())
            return response()->json([
                'message' => 'No existen registros de este comentario',
                'data' => [],
                'status' => 'error'
            ], 200);

        if ($comentario->delete())
            return response()->json([
                'message' => 'Comentario eliminado',
                'data' => [],
                'status' => 'ok'
            ], 200);
            
        return response()->json([
            'message' => 'Ocurrió un error',
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
    public function updateComentario(Request $request, $id)
    {
        if(!$request->has('comentario'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $comentario = Comentarios::where(['id' => $id]);

        if(!$comentario->exists())
            return response()->json([
                'message' => 'No existen registros de ese comentario',
                'data' => [],
                'status' => 'error'
            ], 200);

        $values = ['observacion' => $request->get('comentario')];

        if ($comentario->update($values))
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
     * Display the specified resource.
     *
     * @param  int  $seguimiento_id
     * @return \Illuminate\Http\Response
     */
    public function showComentarioBySeguimiento($seguimiento_id)
    {
        $seguimiento = Seguimiento::where(['id' => $seguimiento_id])->exists();

        if(!$seguimiento)
            return response()->json([
                'message' => 'No existen registros de ese seguimiento',
                'data' => [],
                'status' => 'error'
        ], 200);

        $comentario = Comentarios::where(['seguimiento_id' => $seguimiento_id])->orderBy('created_at', 'DESC')
            ->with(['seguimiento', 'evidencias', 'autor'])->get()->toArray();

        if (count($comentario) > 0)
            return response()->json([
                'message' => 'Consulta exitosa',
                'data' => $comentario,
                'status' => 'ok'
            ], 200);

        return response()->json([
            'message' => 'No existen registros',
            'data' => [],
            'status' => 'error'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeEvidencia(Request $request){
        if(!$request->has('comentario_id'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $comentario = Comentarios::where(['id' => $request->get('comentario_id')]);

        if (!$comentario->exists())
            return response()->json([
                'message' => 'No existen registros de este comentario',
                'data' => [],
                'status' => 'error'
            ], 200);

        if ($request->hasFile('evidencia')) {
            $file = $request->file('evidencia');

            $time = time();
            $file->storeAs('public', $time . '-' . $file->getClientOriginalName());
            $url = 'storage/' . $time . '-' . $file->getClientOriginalName();

            $comentario_id = $comentario->get()->toArray()[0]['id'];

            $evidencia = new Evidencias([
                'url' => $url, 
                'comentario_id' => $comentario_id, 
            ]);

            if (!$evidencia->save())
                return response()->json([
                    'message' => 'Ha ocurido un error',
                    'data' => [],
                    'status' => 'error'
                ], 200);
        }

        return response()->json([
            'message' => 'Evidencia cargada',
            'data' => [],
            'status' => 'ok'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyEvidencia($id)
    {
        $evidencia = Evidencias::where(['id' => $id]);

        if(!$evidencia->exists())
            return response()->json([
                'message' => 'No existen registros de este evidencia',
                'data' => [],
                'status' => 'error'
            ], 200);

        if ($evidencia->delete())
            return response()->json([
                'message' => 'evidencia eliminado',
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
