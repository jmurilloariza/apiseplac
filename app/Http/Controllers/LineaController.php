<?php

namespace App\Http\Controllers;

use App\Models\Eje;
use App\Models\Linea;
use Illuminate\Http\Request;

class LineaController extends Controller
{

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
            'data' => Linea::with(['eje', 'programas'])->get()->toArray(),
            'status' => 'ok'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!$request->has('lineas') or !$request->has('eje_id'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 400);

        $eje = Eje::where(['id' => $request->get('eje_id')])->get()->toArray();

        if (count($eje) != 1)
            return response()->json([
                'message' => 'Eje no encontrado',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 404);

        $lineas = $request->get('lineas');

        for ($i = 0, $long = count($lineas); $i < $long; $i++) {
            if (!isset($lineas[$i]['codigo']) or !isset($lineas[$i]['nombre']) or !isset($lineas[$i]['descripcion']))
                return response()->json([
                    'message' => 'Faltan datos',
                    'data' => $request->toArray(),
                    'status' => 'error'
                ], 400);

            $line = Linea::where(['codigo' => $lineas[$i]['codigo']])->get()->toArray();

            if (count($line) > 0)
                return response()->json([
                    'message' => 'Ya existe una linea con ese código',
                    'data' => $line,
                    'status' => 'error'
                ], 400);

            $linea = new Linea([
                'nombre' => $lineas[$i]['nombre'],
                'eje_id' => $request->get('eje_id'),
                'codigo' => $lineas[$i]['codigo'],
                'descripcion' => $lineas[$i]['descripcion']
            ]);
            if (!$linea->save())
                return response()->json([
                    'message' => 'Ha ocurido un error',
                    'data' => [],
                    'status' => 'error'
                ], 500);
        }

        return response()->json([
            'message' => 'Linea creada',
            'data' => [],
            'status' => 'ok'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $eje = Linea::where(['id' => $id])->with(['programas', 'eje'])->get()->toArray();

        if (count($eje) > 0)
            return response()->json([
                'message' => 'Consulta exitosa',
                'data' => $eje[0],
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
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$request->has('nombre') OR !$request->has('codigo') OR !$request->has('descripcion'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 400);

        $linea = Linea::orWhere(['id' => $id, 'codigo' => $request->has('codigo')]);

        if (count($linea->get()->toArray()) == 0)
            return response()->json([
                'message' => 'No existen registros',
                'data' => [],
                'status' => 'error'
            ], 404);
        else if ($linea->update(['nombre' => $request->get('nombre'), 'codigo' => $request->get('codigo'), 'descripcion' => $request->get('descripcion')]))
            return response()->json([
                'message' => 'Actualización exitosa',
                'data' => [],
                'status' => 'ok'
            ], 200);
        return response()->json([
            'message' => 'Ha ocurido un error',
            'data' => [],
            'status' => 'error'
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Linea::find($id)->delete())
            return response()->json([
                'message' => 'Linea eliminado',
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
