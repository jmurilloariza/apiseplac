<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Facultad;
use App\Models\ProgramaAcademico;
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

        if ($facultad->get()->toArray()[0]['codigo'] != $request->get('codigo')) {
            $existencias = Facultad::where(['codigo' => $request->get('codigo')])->get()->toArray();
            if (count($existencias) >= 1)
                return response()->json([
                    'message' => 'Ya existe el codigo',
                    'data' => [],
                    'status' => 'error'
                ], 200);
        }

        if ($facultad->update(['nombre' => $request->get('nombre'), 'codigo' => $request->get('codigo')]))
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

        if (!$request->has('departamentos') or $request->has('facultad_id'))
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
            if (!isset($departamentos[$i]['nombre']) or !isset($departamentos[$i]['codigo']))
                return response()->json([
                    'message' => 'Faltan datos',
                    'data' => [],
                    'status' => 'error'
                ], 200);

            $departamento = new Departamento([
                'nombre' => $departamentos[$i]['nombre'],
                'codigo' => $departamentos[$i]['codigo'],
                'facultad_id' => $request->get('facultad_id')
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

        $facultad = Facultad::where(['id' => $request->get('facultad_id')]);

        if (count($facultad->get()->toArray()) == 0)
            return response()->json([
                'message' => 'No existen registros de esa facultad',
                'data' => [],
                'status' => 'error'
            ], 200);

        $departamento = Departamento::where(['id' => $id]);

        if (count($departamento->get()->toArray()) == 0)
            return response()->json([
                'message' => 'No existen registros de ese departamento',
                'data' => [],
                'status' => 'error'
            ], 200);

        if ($departamento->get()->toArray()[0]['codigo'] != $request->get('codigo')) {
            $existencias = Departamento::where(['codigo' => $request->get('codigo')])->get()->toArray();
            if (count($existencias) >= 1)
                return response()->json([
                    'message' => 'Ya existe el codigo',
                    'data' => [],
                    'status' => 'error'
                ], 200);
        }

        $values = [
            'nombre' => $request->get('nombre'),
            'codigo' => $request->get('codigo'),
            'facultad_id' => $request->get('facultad_id')
        ];

        if ($departamento->update($values))
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
    public function destroyDepartamento($id)
    {
        if (Departamento::find($id)->delete())
            return response()->json([
                'message' => 'Departamento eliminado',
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
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function showDepartamento($id)
    {
        $departamento = Departamento::where(['id' => $id])->with(['facultad', 'programasAcademicos'])->get()->toArray();

        if (count($departamento) > 0)
            return response()->json([
                'message' => 'Consulta exitosa',
                'data' => $departamento[0],
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexProgramaAcademico()
    {
        return response()->json([
            'message' => 'Consulta exitosa',
            'data' => ProgramaAcademico::with(['departamento', 'usuarios', 'planes'])->get()->toArray(),
            'status' => 'ok'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeProgramaAcademico(Request $request)
    {

        if (!$request->has('programasAcademicos') or !$request->has('departamento_id'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $departamento = Departamento::where(['id' => $request->has('departamento_id')]);

        if (count($departamento->get()->toArray()) == 0)
            return response()->json([
                'message' => 'No existen registros de ese departamento',
                'data' => [],
                'status' => 'error'
            ], 404);

        $programasAcademicos = $request->get('programasAcademicos');

        for ($i = 0, $long = count($programasAcademicos); $i < $long; $i++) {
            if (!isset($programasAcademicos[$i]['nombre']) or !isset($programasAcademicos[$i]['codigo']))
                return response()->json([
                    'message' => 'Faltan datos',
                    'data' => [],
                    'status' => 'error'
                ], 200);

            $programaAcademico = new ProgramaAcademico([
                'nombre' => $programasAcademicos[$i]['nombre'],
                'codigo' => $programasAcademicos[$i]['codigo'],
                'departamento_id' => $request->get('departamento_id')
            ]);

            if (!$programaAcademico->save())
                response()->json([
                    'message' => 'Ha ocurido un error',
                    'data' => [],
                    'status' => 'error'
                ], 500);
        }

        return response()->json([
            'message' => 'Programa creado',
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
    public function updateProgramaAcademico(Request $request, $id)
    {
        if (!$request->has('nombre') or !$request->has('codigo') or !$request->has('departamento_id'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 400);

        $departamento = Departamento::where(['id' => $request->get('departamento_id')]);

        if (count($departamento->get()->toArray()) == 0)
            return response()->json([
                'message' => 'No existen registros de ese departamento',
                'data' => [],
                'status' => 'error'
            ], 200);

        $programaAcademico = ProgramaAcademico::where(['id' => $id]);

        if (count($programaAcademico->get()->toArray()) == 0)
            return response()->json([
                'message' => 'No existen registros de ese programa academico',
                'data' => [],
                'status' => 'error'
            ], 200);


        if ($programaAcademico->get()->toArray()[0]['codigo'] != $request->get('codigo')) {
            $existencias = ProgramaAcademico::where(['codigo' => $request->get('codigo')])->get()->toArray();
            if (count($existencias) >= 1)
                return response()->json([
                    'message' => 'Ya existe el codigo',
                    'data' => [],
                    'status' => 'error'
                ], 200);
        }

        $values = [
            'nombre' => $request->get('nombre'),
            'codigo' => $request->get('codigo'),
            'departamento_id' => $request->get('departamento_id')
        ];

        if ($programaAcademico->update($values))
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
    public function destroyProgramaAcademico($id)
    {
        if (ProgramaAcademico::find($id)->delete())
            return response()->json([
                'message' => 'Programa academico eliminado',
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
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function showProgramaAcademico($id)
    {
        $programaAcademico = ProgramaAcademico::where(['id' => $id])->with(['departamento', 'usuarios', 'planes'])->get()->toArray();

        if (count($programaAcademico) > 0)
            return response()->json([
                'message' => 'Consulta exitosa',
                'data' => $programaAcademico[0],
                'status' => 'ok'
            ], 200);
        else
            return response()->json([
                'message' => 'No existen registros',
                'data' => [],
                'status' => 'error'
            ], 404);
    }
}