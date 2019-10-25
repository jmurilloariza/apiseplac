<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
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
            'data' => Facultad::with(['departamentos'])->get()->toArray(),
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

        $facultades = $request->get('facultades');

        for ($i = 0, $long = count($facultades); $i < $long; $i++) {
            if (!isset($facultades[$i]['nombre']) or !isset($facultades[$i]['codigo']))
                return response()->json([
                    'message' => 'Faltan datos',
                    'data' => [],
                    'status' => 'error'
                ], 200);

            $facultad = new Facultad(['nombre' =>  $facultades[$i]['nombre'], 'codigo' =>  $facultades[$i]['codigo']]);

            if (!$facultad->save())
                response()->json([
                    'message' => 'Ha ocurido un error',
                    'data' => [],
                    'status' => 'error'
                ], 500);
        }

        return response()->json([
            'message' => 'Facultad creada',
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
        if (!$request->has('nombre') or !$request->has('codigo'))
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
        else if ($facultad->update(['nombre' => $request->get('nombre'), 'codigo' => $request->has('codigo')]))
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexDepartamento()
    {
        return response()->json([
            'message' => 'Consulta exitosa',
            'data' => Departamento::with(['facultad', 'programasAcademicos'])->get()->toArray(),
            'status' => 'ok'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeDepartamento(Request $request)
    {

        if (!$request->has('departamentos'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $facultad = Facultad::where(['id' => $request->has('facultad_id')]);

        if (count($facultad->get()->toArray()) == 0)
            return response()->json([
                'message' => 'No existen registros de esa facultad',
                'data' => [],
                'status' => 'error'
            ], 404);

        $departamentos = $request->get('departamentos');

        for ($i = 0, $long = count($departamentos); $i < $long; $i++) {
            if (!isset($departamentos[$i]['nombre']) or !isset($departamentos[$i]['codigo']) or !isset($departamentos[$i]['facultad_id']))
                return response()->json([
                    'message' => 'Faltan datos',
                    'data' => [],
                    'status' => 'error'
                ], 200);

            $departamento = new Departamento([
                'nombre' => $departamentos[$i]['nombre'],
                'codigo' => $departamentos[$i]['codigo'],
                'facultad_id' => $departamentos[$i]['facultad_id']
            ]);

            if (!$departamento->save())
                response()->json([
                    'message' => 'Ha ocurido un error',
                    'data' => [],
                    'status' => 'error'
                ], 500);
        }

        return response()->json([
            'message' => 'Departamento creada',
            'data' => [],
            'status' => 'ok'
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateDepartamento(Request $request, $id)
    {
        if (!$request->has('nombre') or !$request->has('codigo') or !$request->has('facultad_id'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 400);

        $facultad = Facultad::where(['id' => $request->has('facultad_id')]);

        if (count($facultad->get()->toArray()) == 0)
            return response()->json([
                'message' => 'No existen registros de esa facultad',
                'data' => [],
                'status' => 'error'
            ], 200);

        $departamento = Departamento::where(['id' => $id]);

        if (count($departamento->get()->toArray()) == 0)
            return response()->json([
                'message' => 'No existen registros de esa facultad',
                'data' => [],
                'status' => 'error'
            ], 200);

        if ($departamento->update(['nombre' => $request->get('nombre'), 'codigo' => $request->has('codigo'), 'facultad_id' => $request->has('facultad_id')]))
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
}
