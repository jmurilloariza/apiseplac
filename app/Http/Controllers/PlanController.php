<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\ProgramaAcademico;
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
        if (!$request->has('fecha_inicio') or !$request->has('fecha_fin') or !$request->has('programa_academico_id') or !$request->hasFile('documento'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $programaAcademico = ProgramaAcademico::where(['id' => $request->get('programa_academico_id')]);

        if (count($programaAcademico->get()->toArray()) != 1)
            return response()->json([
                'message' => 'No existen registros del programa academico',
                'data' => [],
                'status' => 'error'
            ], 200);

        $file = $request->file('documento');
        $time = time();
        $file->storeAs('public', $time . '-' . $file->getClientOriginalName());

        $plan = new Plan([
            'fecha_inicio' => $request->get('fecha_inicio'),
            'fecha_fin' => $request->get('fecha_fin'),
            'programa_academico_id' => $request->get('programa_academico_id'),
            'url_documento' => 'storage/' . $time . '-' . $file->getClientOriginalName()
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
        //
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
        //
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexProyecto()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeProyecto(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showProyecto($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProyecto(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyProyecto($id)
    {
        //
    }
}
