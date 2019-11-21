<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\PlanProyecto;
use App\Models\Seguimiento;
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
        if(!$request->has('actividad_id') or !$request->has('periodo_evaluado') or !$request->has('fecha_seguimiento') or 
            !$request->has('valoracion') or !$request->has('situacion_actual'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $actividad = Actividad::where(['actividad'])->exists();

        if(!$actividad)
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
            response()->json([
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
        if(!$request->has('actividad_id') or !$request->has('periodo_evaluado') or !$request->has('fecha_seguimiento') or 
            !$request->has('valoracion') or !$request->has('situacion_actual'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $seguimiento = Seguimiento::where(['id' => $id]);

        if(!$seguimiento->exists())
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

    public function iniciarSeguimientoProyecto(Request $request){
        
    }

    public function calcularPeriodosPendienteSeguimiento($plan_id){
        $planesProyectos = PlanProyecto::where(['plan_id' => $plan_id])->with(['proyecto.actividades', 'plan'])->get();
        return response()->json($planesProyectos->toArray());
    }
}
