<?php

namespace App\Http\Controllers;

use App\Models\Facultad;
use Illuminate\Http\Request;

class FacultadController extends Controller
{
    /**
     * FacultadController constructor.
     */
    public function __construct()
    {
        /*$this->middleware('auth:api');*/ }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'message' => 'Consulta exitosa',
            'data' => Facultad::with(['departamento'])->get()->toArray(),
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
        if (!$request->has('facultades'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $facultad = $request->get('facultades');

        for ($i = 0, $long = count($facultad); $i < $long; $i++) {
            if (!isset($facultad[$i]['nombre']) or !isset($facultad[$i]['nombre']))
                return response()->json([
                    'message' => 'Faltan datos',
                    'data' => $request->toArray(),
                    'status' => 'error'
                ], 200);

            $facultad = new Facultad(['nombre' =>  $facultad[$i]['nombre'], 'codigo' =>  $facultad[$i]['codigo']]);

            if (!$facultad->save())
                response()->json([
                    'message' => 'Ha ocurido un error',
                    'data' => [],
                    'status' => 'error'
                ], 500);
        }

        return response()->json([
            'message' => 'Dependencia creada',
            'data' => [],
            'status' => 'ok'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $facultad = Facultad::where(['id' => $id])->with(['departamentos'])->get()->toArray();

        if (count($facultad) > 0)
            return response()->json([
                'message' => 'Consulta exitosa',
                'data' => $facultad[0],
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
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$request->has('nombre'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 400);

        $facultad = Facultad::where(['id' => $id]);

        if (count($facultad->get()->toArray()) == 0)
            return response()->json([
                'message' => 'No existen registros',
                'data' => [],
                'status' => 'error'
            ], 404);
        else if ($facultad->update(['nombre' => $request->get('nombre')]))
            return response()->json([
                'message' => 'Actualización exitosa',
                'data' => [],
                'status' => 'ok'
            ], 200);
        else return response()->json([
            'message' => 'Ha ocurido un error',
            'data' => [],
            'status' => 'error'
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Facultad::find($id)->delete())
            return response()->json([
                'message' => 'Dependencia eliminada',
                'data' => [],
                'status' => 'ok'
            ], 200);
        else
            return response()->json([
                'message' => 'Ocurrió un error',
                'data' => [],
                'status' => 'error'
            ], 500);
    }
}
