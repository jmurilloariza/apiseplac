<?php

namespace App\Http\Controllers;

use App\Models\Eje;
use App\Models\Linea;
use Illuminate\Http\Request;

class EjeController extends Controller
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
            'data' => Eje::with(['lineas.programas'])->get()->toArray(),
            'status' => 'ok'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->has('ejes'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $ejes = $request->get('ejes');

        for ($i = 0, $long = count($ejes); $i < $long; $i++) {
            $ej = $ejes[$i];

            $eje = Eje::where(['codigo' => $ej['codigo']])->get()->toArray();

            if (count($eje) > 0)
                return response()->json([
                    'message' => 'Ya existe un eje con ese código',
                    'data' => $eje,
                    'status' => 'error'
                ], 200);

            $eje = new Eje(['nombre' => $ej['nombre'], 'descripcion' => $ej['descripcion'], 'codigo' => $ej['codigo']]);

            if (!$eje->save())
                response()->json([
                    'message' => 'Ha ocurido un error',
                    'data' => [],
                    'status' => 'error'
                ], 200);
        }

        return response()->json([
            'message' => 'Eje creado',
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
        $eje = Eje::where(['id' => $id])->with(['lineas.programas'])->get()->toArray();

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
            ], 200);
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

        // return response()->json([$request->toArray(), intval($id)]);

        if (!$request->has('nombre') or !$request->has('descripcion') or !$request->has('codigo'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $eje = Eje::where('id', '=', intval($id));

        if (count($eje->get()->toArray()) == 0)
            return response()->json([
                'message' => 'No existen registros',
                'data' => [],
                'status' => 'error'
            ], 200);
        else if ($eje->update(['nombre' => $request->get('nombre'), 'descripcion' => $request->get('descripcion'), 'codigo' => $request->get('codigo')]))
            return response()->json([
                'message' => 'Actualización exitosa',
                'data' => [],
                'status' => 'ok'
            ], 200);
        else return response()->json([
            'message' => 'Ha ocurido un error',
            'data' => [],
            'status' => 'error'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Eje::find($id)->delete())
            return response()->json([
                'message' => 'Eje eliminado',
                'data' => [],
                'status' => 'ok'
            ], 200);
        else
            return response()->json([
                'message' => 'Ocurrió un error',
                'data' => [],
                'status' => 'error'
            ], 200);
    }
}
