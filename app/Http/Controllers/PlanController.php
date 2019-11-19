<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlanProyecto;
use App\Models\ProgramaAcademico;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'message' => 'Consulta exitosa',
            'data' => Plan::with(['programaAcademico', 'proyectos'])->get()->toArray(),
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
        if (!$request->has('fecha_inicio') or !$request->has('fecha_fin') or !$request->has('programa_academico_id') or !$request->has('nombre'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        // if (!$request->hasFile('documento'))
        //     return response()->json([
        //         'message' => 'Debe enviar el documento del plan',
        //         'data' => [],
        //         'status' => 'error'
        //     ], 200);

        $programaAcademico = ProgramaAcademico::where(['id' => $request->get('programa_academico_id')]);

        if (count($programaAcademico->get()->toArray()) != 1)
            return response()->json([
                'message' => 'No existen registros del programa academico',
                'data' => [],
                'status' => 'error'
            ], 200);

        // $file = $request->file('documento');
        // $time = time();
        // $file->storeAs('public', $time . '-' . $file->getClientOriginalName());

        // $plan = new Plan([
        //     'fecha_inicio' => $request->get('fecha_inicio'),
        //     'fecha_fin' => $request->get('fecha_fin'),
        //     'programa_academico_id' => $request->get('programa_academico_id'),
        //     'url_documento' => 'storage/' . $time . '-' . $file->getClientOriginalName()
        // ]);

        $plan = new Plan([
            'fecha_inicio' => $request->get('fecha_inicio'),
            'fecha_fin' => $request->get('fecha_fin'),
            'programa_academico_id' => $request->get('programa_academico_id'),
            'url_documento' => '',
            'nombre' => $request->get('nombre')
        ]);

        if (!$plan->save())
            response()->json([
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
        $plan = Plan::where(['id' => $id])->with(['programaAcademico', 'proyectos.programas.linea.eje', 'proyectos.actividades'])
            ->get()->toArray();

        if (count($plan) > 0)
            return response()->json([
                'message' => 'Consulta exitosa',
                'data' => $plan[0],
                'status' => 'ok'
            ], 200);
        else
            return response()->json([
                'message' => 'No existen registros',
                'data' => [],
                'status' => 'error'
            ], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
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

        if (!$request->has('fecha_inicio') or !$request->has('fecha_fin') or !$request->has('programa_academico_id') or !$request->has('nombre'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $values = [
            'fecha_inicio' => $request->get('fecha_inicio'),
            'fecha_fin' => $request->get('fecha_fin'),
            'programa_academico_id' => $request->get('programa_academico_id'),
            'url_documento' => '',
            'nombre' => $request->get('nombre')
        ];

        if ($plan->update($values))
            return response()->json([
                'message' => 'Actualización exitosa',
                'data' => [$values],
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
            if (!Proyecto::where(['id' => $pr])->exists())
                return response()->json([
                    'message' => 'No existen registros de un plan con el identificador ' . $pr,
                    'data' => [],
                    'status' => 'error'
                ], 200);

            $proyecto = new PlanProyecto([
                'plan_id' => $request->get('plan_id'),
                'proyecto_id' => $pr
            ]);

            if (!$proyecto->save())
                return response()->json([
                    'message' => 'Ha ocurido un error',
                    'data' => [],
                    'status' => 'error'
                ], 200);
        }

        return response()->json([
            'message' => 'Proyectos asociados al plan',
            'data' => [],
            'status' => 'ok'
        ], 201);
    }
}
