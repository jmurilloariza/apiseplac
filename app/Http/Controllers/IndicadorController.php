<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Indicador;
use Illuminate\Http\Request;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class IndicadorController extends Controller
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
        return response()->json([
            'message' => 'Consulta exitosa',
            'data' => Indicador::with('actividades')->get()->toArray(),
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
        if (!$request->has('indicadores'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 400);

        $indicadores = $request->get('indicadores');

        for ($i = 0, $long = count($indicadores); $i < $long; $i++) {
            $nombre = $indicadores[$i];

            $indicador = new Indicador(['nombre' => $nombre]);

            if (!$indicador->save())
                response()->json([
                    'message' => 'Ha ocurido un error',
                    'data' => [],
                    'status' => 'error'
                ], 500);
        }

        return response()->json([
            'message' => 'Indicador creado',
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
        $indicador = Indicador::where(['id' => $id])->with('actividades')->get()->toArray();

        if (count($indicador) > 0)
            return response()->json([
                'message' => 'Consulta exitosa',
                'data' => $indicador[0],
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

        $indicador = Indicador::where(['id' => $id]);

        if (count($indicador->get()->toArray()) == 0)
            return response()->json([
                'message' => 'No existen registros',
                'data' => [],
                'status' => 'error'
            ], 404);

        if ($indicador->update(['nombre' => $request->get('nombre')]))
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exists = Actividad::where(['indicador_id' => $id])->exists();

        if($exists)
            return response()->json([
                'message' => 'Existen actividades cuyo indicador es este',
                'data' => [],
                'status' => 'ok'
            ], 200);

        if (Indicador::find($id)->delete())
            return response()->json([
                'message' => 'Indicador eliminado',
                'data' => [],
                'status' => 'ok'
            ], 200);

        return response()->json([
            'message' => 'Ocurrió un error',
            'data' => [],
            'status' => 'error'
        ], 500);
    }
}
