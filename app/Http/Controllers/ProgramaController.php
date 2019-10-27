<?php

namespace App\Http\Controllers;

use App\Models\Linea;
use App\Models\Programa;
use App\Models\ProyectoPrograma;
use Illuminate\Http\Request;

class ProgramaController extends Controller
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
            'data' => Programa::with(['linea'])->get()->toArray(),
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
        if (!$request->has('linea_id') or !$request->has('programas'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $linea = Linea::where(['id' => $request->get('linea_id')])->get()->toArray();

        if (count($linea) != 1)
            return response()->json([
                'message' => 'Linea no encontrada',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $programas = $request->get('programas');

        for ($i = 0, $long = count($programas); $i < $long; $i++) {
            if (!isset($programas[$i]['codigo']) or !isset($programas[$i]['nombre']) or !isset($programas[$i]['descripcion']))
                return response()->json([
                    'message' => 'Faltan datos',
                    'data' => $request->toArray(),
                    'status' => 'error'
                ], 200);

            $program = Programa::where(['codigo' => $programas[$i]['codigo']])->get()->toArray();

            if (count($program) > 0)
                return response()->json([
                    'message' => 'Ya existe un programa con ese código',
                    'data' => $program,
                    'status' => 'error'
                ], 200);

            $programa = new Programa([
                'nombre' => $programas[$i]['nombre'],
                'linea_id' => $request->get('linea_id'),
                'codigo' => $programas[$i]['codigo'],
                'descripcion' => $programas[$i]['descripcion']
            ]);

            if (!$programa->save())
                return response()->json([
                    'message' => 'Ha ocurido un error',
                    'data' => [],
                    'status' => 'error'
                ], 200);
        }

        return response()->json([
            'message' => 'Programa creado',
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
        $programa = Programa::where(['id' => $id])->with(['linea.eje'])->get()->toArray();

        if (count($programa) > 0)
            return response()->json([
                'message' => 'Consulta exitosa',
                'data' => $programa[0],
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
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$request->has('nombre') or !$request->has('codigo') or !$request->has('descripcion'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $programa = Programa::where(['id' => $id]);

        if (count($programa->get()->toArray()) == 0)
            return response()->json([
                'message' => 'No existen registros',
                'data' => [],
                'status' => 'error'
            ], 200);

        if ($programa->get()->toArray()[0]['codigo'] != $request->get('codigo')) {
            $existencias = Programa::where(['codigo' => $request->get('codigo')])->get()->toArray();
            if (count($existencias) >= 1)
                return response()->json([
                    'message' => 'Ya existe el codigo',
                    'data' => [],
                    'status' => 'error'
                ], 200);
        }

        $values = ['nombre' => $request->get('nombre'), 'codigo' => $request->get('codigo'), 'descripcion' => $request->get('descripcion')];

        if ($programa->update($values))
            return response()->json([
                'message' => 'Actualización exitosa',
                'data' => [],
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
        Programa::where(['id' => $id])->delete();
        ProyectoPrograma::where(['programa_id' => $id])->delete();

        return response()->json([
            'message' => 'Ocurrió un error',
            'data' => [],
            'status' => 'error'
        ], 200);
    }
}
