<?php

namespace App\Http\Controllers;

use App\Models\Dependencia;
use Illuminate\Http\Request;

class DependenciaController extends Controller
{
    /**
     * DependenciaController constructor.
     */
    public function __construct()
    {
        /*$this->middleware('auth:api');*/
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
            'data' => Dependencia::with(['planes', 'usuarios'])->get()->toArray(),
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
        if (!$request->has('dependencias'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 400);

        $dependencias = $request->get('dependencias');

        for ($i = 0, $long = count($dependencias); $i < $long; $i++) {
            $nombre = $dependencias[$i];

            $dependencia = new Dependencia(['nombre' => $nombre]);

            if (!$dependencia->save())
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
        $dependencia = Dependencia::where(['id' => $id])->with(['planes', 'usuarios'])->get()->toArray();

        if (count($dependencia) > 0)
            return response()->json([
                'message' => 'Consulta exitosa',
                'data' => $dependencia[0],
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

        $dependencia = Dependencia::where(['id' => $id]);

        if (count($dependencia->get()->toArray()) == 0)
            return response()->json([
                'message' => 'No existen registros',
                'data' => [],
                'status' => 'error'
            ], 404);
        else if ($dependencia->update(['nombre' => $request->get('nombre')]))
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
        if (Dependencia::find($id)->delete())
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
