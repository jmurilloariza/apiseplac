<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\ActividadRecurso;
use App\Models\Programa;
use App\Models\ProgramaAcademico;
use App\Models\Proyecto;
use App\Models\ProyectoPrograma;
use App\Models\Recurso;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'message' => 'Consulta exitosa',
            'data' => Proyecto::with(['programas', 'actividades', 'planesProyectos.proyecto', 'programaAcademico'])->get()->toArray(),
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
        if (!$request->has('nombre') or !$request->has('programa_academico_id') or !$request->has('descripcion') or
            !$request->has('objetivo') or !$request->has('programas'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => [$request->toArray(), 
                !$request->has('nombre') or !$request->has('programa_academico_id') or !$request->has('descripcion') or
                !$request->has('objetivo') or !$request->has('programas')],
                !$request->has('nombre'), 
                !$request->has('programa_academico_id'), 
                !$request->has('descripcion'), 
                !$request->has('objetivo'), 
                !$request->has('programas'), 
                'status' => 'error'
            ], 200);

        $programaAcademico = ProgramaAcademico::where(['id' => $request->get('programa_academico_id')])->exists();

        if (!$programaAcademico)
            return response()->json([
                'message' => 'No existe el plan asociado',
                'data' => [],
                'status' => 'error'
            ], 200);

        $proyecto = new Proyecto([
            'nombre' => $request->get('nombre'),
            'descripcion' => $request->get('descripcion'),
            'objetivo' => $request->get('objetivo'), 
            'programa_academico_id' => $request->get('programa_academico_id')
        ]);

        if (!$proyecto->save())
            return response()->json([
                'message' => 'Ha ocurrido un error inesperado',
                'data' => [],
                'status' => 'error'
            ], 200);

        $programas = $request->get('programas');

        for ($i = 0, $long = count($programas); $i < $long; $i++) {
            if (!Programa::where(['id' => $programas[$i]])->exists())
                return response()->json([
                    'message' => 'No existe un programa asociado a el id: ' . $programas[$i],
                    'data' => [],
                    'status' => 'error'
                ], 200);

            $proyecto_programa = new ProyectoPrograma([
                'programa_id' => $programas[$i],
                'proyecto_id' => $proyecto->id
            ]);

            if (!$proyecto_programa->save())
                return response()->json([
                    'message' => 'Ha ocurrido un error inesperado',
                    'data' => [],
                    'status' => 'error'
                ], 200);
        }

        return response()->json([
            'message' => 'Proyecto creado',
            'data' => [],
            'status' => 'ok'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getProgramaAcademico($programaAcademico){
        $proyectos = Proyecto::with(['programas', 'actividades', 'programaAcademico', 'planesProyectos.proyecto'])
            ->where(['programa_academico_id' => $programaAcademico])->get()->toArray();

        return response()->json([
            'message' => 'Consulta exitosa',
            'data' => $proyectos,
            'status' => 'ok'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeActividad(Request $request)
    {
        if (!$request->has('actividades') or !$request->has('proyecto_id')) {
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);
        }

        $proyecto = Proyecto::where(['id' => $request->get('proyecto_id')])->exists();

        if(!$proyecto)
            return response()->json([
                'message' => 'No existen registros de ese proyecto',
                'data' => [],
                'status' => 'error'
            ], 200);

        $actividades = $request->get('actividades');

        for ($i = 0, $long = count($actividades); $i < $long; $i++) {
            $actividad = $actividades[$i];

            if (!isset($actividad['indicador_id']) or !isset($actividad['nombre']) or !isset($actividad['descripcion']) or
                !isset($actividad['fecha_inicio']) or !isset($actividad['fecha_fin']) or !isset($actividad['costo']) or
                !isset($actividad['unidad_medida']) or !isset($actividad['peso']) or !isset($actividad['recursos'])){
                return response()->json([
                    'message' => 'Faltan datos',
                    'data' => $request->toArray(),
                    'status' => 'error'
                ], 200);
            }

            $model = new Actividad([
                'proyecto_id' => $request->get('proyecto_id'), 
                'indicador_id' => $actividad['indicador_id'], 
                'nombre' => $actividad['nombre'], 
                'descripcion' => $actividad['descripcion'], 
                'fecha_inicio' => $actividad['fecha_inicio'], 
                'fecha_fin' => $actividad['fecha_fin'], 
                'costo' => $actividad['costo'], 
                'unidad_medida' => $actividad['unidad_medida'], 
                'peso' => $actividad['peso']
            ]);

            if(!$model->save())
                return response()->json([
                    'message' => 'Error inesperado al registrar la actividad',
                    'data' => [],
                    'status' => 'error'
                ], 200);

            $recursos = $actividad['recursos'];

            foreach($recursos as $recurso){
                if(!Recurso::where(['id' => $recurso])->exists())
                    return response()->json([
                        'message' => 'No existen registros recurso',
                        'data' => [],
                        'status' => 'error'
                    ], 200);
                
                $actividadRecurso = new ActividadRecurso([
                    'actividad_id' => $model->id, 
                    'recursos_id' => $recurso
                ]);

                if(!$actividadRecurso->save())
                    return response()->json([
                        'message' => 'Error inesperado al registrar el recurso para la actividad',
                        'data' => [],
                        'status' => 'error'
                    ], 200);
            }
        }

        return response()->json([
            'message' => 'actividades registradas al proyecto',
            'data' => [],
            'status' => 'ok'
        ], 200);
    }
}
