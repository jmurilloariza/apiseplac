<?php

namespace App\Http\Controllers;

use App\Models\Eje;
use App\Models\Linea;
use App\Models\Programa;
use Illuminate\Http\Request;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class LineaController extends Controller
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
            ], 200);

        $eje = Eje::where(['id' => $request->get('eje_id')])->get()->toArray();

        if (count($eje) != 1)
            return response()->json([
                'message' => 'Eje no encontrado',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $lineas = $request->get('lineas');

        for ($i = 0, $long = count($lineas); $i < $long; $i++) {
            if (!isset($lineas[$i]['codigo']) or !isset($lineas[$i]['nombre']) or !isset($lineas[$i]['descripcion']))
                return response()->json([
                    'message' => 'Faltan datos',
                    'data' => $request->toArray(),
                    'status' => 'error'
                ], 200);

            $line = Linea::where(['codigo' => $lineas[$i]['codigo']])->get()->toArray();

            if (count($line) > 0)
                return response()->json([
                    'message' => 'Ya existe una linea con ese código',
                    'data' => $line,
                    'status' => 'error'
                ], 200);

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
                ], 200);
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
        
        return response()->json([
            'message' => 'No existen registros',
            'data' => [],
            'status' => 'error'
        ], 200);
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
        if (!$request->has('nombre') or !$request->has('codigo')
            or !$request->has('descripcion') or !$request->has('eje_id'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $eje = Eje::where(['id' => $request->get('eje_id')])->exists();

        if(!$eje)
            return response()->json([
                'message' => 'No existen registros de un eje con ese id',
                'data' => [],
                'status' => 'error'
            ], 200);

        $linea = Linea::where(['id' => $id]);

        if (count($linea->get()->toArray()) == 0)
            return response()->json([
                'message' => 'No existen registros',
                'data' => [],
                'status' => 'error'
            ], 200);

        if ($linea->get()->toArray()[0]['codigo'] != $request->get('codigo')) {
            $existencias = Linea::where(['codigo' => $request->get('codigo')])->exists();
            if ($existencias)
                return response()->json([
                    'message' => 'Ya existe el codigo',
                    'data' => [],
                    'status' => 'error'
                ], 200);
        }

        $values = [
            'nombre' => $request->get('nombre'),
            'codigo' => $request->get('codigo'),
            'descripcion' => $request->get('descripcion'),
            'eje_id' => $request->get('eje_id')
        ];

        if ($linea->update($values))
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $linea = Linea::find($id);
        $programas = $linea->with(['programas.proyectos'])->get()->toArray()[0]['programas'];

        for ($i = 0, $long = count($programas); $i < $long; $i++) {
            if (count($programas[$i]['proyectos']) > 0) {
                return response()->json([
                    'message' => 'NO es posible eliminar la linea ya que tiene programas asignados a algunos proyectos',
                    'data' => [],
                    'status' => 'error'
                ], 200);
            }
        }

        $linea->update(['codigo' => null]);
        Programa::where(['linea_id' => $id])->update(['codigo' => null]);
        Programa::where(['linea_id' => $id])->delete();

        if ($linea->delete())
            return response()->json([
                'message' => 'Linea eliminado',
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
