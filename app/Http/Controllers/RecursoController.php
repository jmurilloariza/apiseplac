<?php

namespace App\Http\Controllers;

use App\Models\Recurso;
use Illuminate\Http\Request;

class RecursoController extends Controller
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
            'data' => Recurso::all()->toArray(),
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
        if (!$request->has('nombre'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 400);

        $eje = new Recurso(['nombre' => $request->get('nombre')]);

        if ($eje->save())
            return response()->json([
                'message' => 'Recurso creado',
                'data' => [$eje->toArray()],
                'status' => 'ok'
            ], 201);
        else
            return response()->json([
                'message' => 'Ha ocurido un error',
                'data' => [],
                'status' => 'error'
            ], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $eje = Recurso::where(['id' => $id])->get()->toArray();

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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
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

        $eje = Recurso::where(['id' => $id]);

        if (count($eje->get()->toArray()) == 0)
            return response()->json([
                'message' => 'No existen registros',
                'data' => [],
                'status' => 'error'
            ], 404);
        else if ($eje->update(['nombre' => $request->get('nombre')]))
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Recurso::find($id)->delete())
            return response()->json([
                'message' => 'Recurso eliminado',
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
