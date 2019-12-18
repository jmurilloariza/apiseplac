<?php

namespace App\Http\Controllers;

use App\Mail\Responsable;
use App\Models\Actividad;
use App\Models\ActividadRecurso;
use App\Models\Indicador;
use App\Models\Observacion;
use App\Models\PlanActividad;
use App\Models\Programa;
use App\Models\ProgramaAcademico;
use App\Models\Proyecto;
use App\Models\ProyectoPrograma;
use App\Models\ProyectosUsuario;
use App\Models\Recurso;
use App\Models\Seguimiento;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 2.0
 */

class ProyectoController extends Controller
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
            'data' => Proyecto::with([
                'programas',
                'actividades.actividadesRecursos.recurso',
                'responsables.usuario',
                'programaAcademico',
            ])->get()->toArray(),
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
            !$request->has('objetivo') or !$request->has('programas') or !$request->has('usuario_id') )
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(), 
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

        $usuario = Usuario::where(['id' => $request->get('usuario_id')])->exists();

        if(!$usuario)
            return response()->json([
                'message' => 'No existe un usuario con ese id',
                'data' => [],
                'status' => 'error'
            ], 200);

        $proyectoUsuario = new ProyectosUsuario([
            'proyecto_id' => $proyecto->id, 
            'usuario_id' => $request->get('usuario_id')
        ]);

        $proyectoUsuario->save();

        return response()->json([
            'message' => 'Proyecto creado',
            'data' => [],
            'status' => 'ok'
        ], 200);
    }

    /**
     * Display the specified resource. adentro
     *
     * @param  int  $proyecto_id
     * @param  int  $plan_id
     * @return \Illuminate\Http\Response
     */
    public function showProyectoPlan($proyecto_id, $plan_id){
        $proyecto = Proyecto::where(['id' => $proyecto_id])->with([
            'programas',
            'actividades.actividadesRecursos.recurso',
            'responsables.usuario',
            'actividades.planActividad.plan',
            'programaAcademico',
        ])->get()->toArray()[0];

        $actividades = $proyecto['actividades'];
        $actividadesVinculadas = [];
        
        for ($i=0; $i < count($actividades); $i++) { 
            $actividad = $actividades[$i];
            $planesActividades = $actividad['plan_actividad'];

            foreach ($planesActividades as $planActividad) {
                if($planActividad['plan_id'] == $plan_id){
                    $data_actividad = [
                        'id' => $planActividad['id'],
                        'proyecto_id' => $actividad['proyecto_id'],
                        'indicador_id' => $actividad['indicador_id'],
                        'nombre' => $actividad['nombre'],
                        'descripcion' => $actividad['descripcion'],
                        'fecha_inicio' => $planActividad['fecha_inicio'],
                        'fecha_fin' => $planActividad['fecha_fin'],
                        'costo' => $planActividad['costo'],
                        'unidad_medida' => $actividad['unidad_medida'],
                        'peso' => $planActividad['peso'],
                        'estado' => $planActividad['estado'],
                        'actividades_recursos' => $actividad['actividades_recursos']
                    ];

                    array_push($actividadesVinculadas, $data_actividad);
                    break;
                }
            }
        }

        $proyecto['actividades'] = $actividadesVinculadas;

        if (count($proyecto) > 0)
            return response()->json([
                'message' => 'Consulta exitosa',
                'data' => $proyecto,
                'status' => 'ok'
            ], 200);

        return response()->json([
            'message' => 'No existen registros',
            'data' => [],
            'status' => 'error'
        ], 404);
    }

    /**
     * Display the specified resource. por fuera
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $proyecto = Proyecto::where(['id' => $id])->with([
            'programas',
            'actividades.actividadesRecursos.recurso',
            'responsables.usuario',
            'actividades',
            'programaAcademico',
        ])->get()->toArray()[0];

        if (count($proyecto) > 0)
            return response()->json([
                'message' => 'Consulta exitosa',
                'data' => $proyecto,
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$request->has('nombre') or !$request->has('programa_academico_id') or !$request->has('descripcion') or
            !$request->has('objetivo') or !$request->has('programas'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(), 
                'status' => 'error'
            ], 200);

        $programaAcademico = ProgramaAcademico::where(['id' => $request->get('programa_academico_id')])->exists();

        if (!$programaAcademico)
            return response()->json([
                'message' => 'No existe el plan asociado',
                'data' => [],
                'status' => 'error'
            ], 200);

        $proyecto = Proyecto::where(['id' => $id]);

        if(count($proyecto->get()->toArray()) == 0)
            return response()->json([
                'message' => 'No existen registros de ese proyecto',
                'data' => [],
                'status' => 'error'
            ], 200);

        $data = [
            'nombre' => $request->get('nombre'),
            'descripcion' => $request->get('descripcion'),
            'objetivo' => $request->get('objetivo'), 
            'programa_academico_id' => $request->get('programa_academico_id')
        ];

        if(!$proyecto->update($data))
            return response()->json([
                'message' => 'Ha ocurido un error al actualizar el proyecto',
                'data' => [],
                'status' => 'error'
            ], 200);

        ProyectoPrograma::where(['proyecto_id' => $id])->delete();

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
                'proyecto_id' => $id
            ]);

            if (!$proyecto_programa->save())
                return response()->json([
                    'message' => 'Ha ocurrido un error inesperado',
                    'data' => [],
                    'status' => 'error'
                ], 200);
        }

        return response()->json([
            'message' => 'Actualización exitosa',
            'data' => [],
            'status' => 'ok'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $proyecto = Proyecto::where(['id' => $id]);

        if(!$proyecto->exists())
            return response()->json([
                'message' => 'No existen registros de ese proyecto',
                'data' => [],
                'status' => 'error'
            ], 200);

        $relaciones = $proyecto->with([
            'programas',
            'actividades.actividadesRecursos.recurso',
            'actividades.planActividad',
            'programaAcademico',
        ])->get()->toArray()[0];

        $actividades = $relaciones['actividades'];

        foreach ($actividades as $actividad) {
            if(count($actividad['plan_actividad']) > 0)
                return response()->json([
                    'message' => 'El proyeto está asociado a un plan',
                    'data' => [],
                    'status' => 'error'
                ], 200);
        }

        ProyectoPrograma::where(['proyecto_id' => $id])->delete();
        Actividad::where(['proyecto_id' => $id])->delete();
        
        if ($proyecto->delete())
            return response()->json([
                'message' => 'proyecto eliminado',
                'data' => [],
                'status' => 'ok'
            ], 200);
            
        return response()->json([
            'message' => 'Ocurrió un error',
            'data' => [],
            'status' => 'error'
        ], 200);
    }

    /**
     * Permite obtener todos los proyectos asociados a un programa academico en especifico
     * @param $programaAcademico
     * @return \Illuminate\Http\JsonResponse
     */
    public function showByPogramaAcademico($programaAcademico){
        $proyectos = Proyecto::with(['programas', 'actividades.planActividad.plan', 'programaAcademico', 'responsables.usuario'])
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showActividad($id)
    {
        $actividad = Actividad::where(['id' => $id])
            ->with(['indicador', 'proyecto', 'actividadesRecursos.recurso'])
            ->get()->toArray();

        if (count($actividad) > 0)
            return response()->json([
                'message' => 'Consulta exitosa',
                'data' => $actividad[0],
                'status' => 'ok'
            ], 200);

        return response()->json([
            'message' => 'No existen registros',
            'data' => [],
            'status' => 'error'
        ], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPlanActividad($id)
    {
        $actividad = PlanActividad::where(['id' => $id])
            ->with(['actividad.indicador', 'actividad.proyecto', 'actividad.actividadesRecursos.recurso'])
            ->get()->toArray();

        if (count($actividad) > 0)
            return response()->json([
                'message' => 'Consulta exitosa',
                'data' => $actividad[0],
                'status' => 'ok'
            ], 200);

        return response()->json([
            'message' => 'No existen registros',
            'data' => [],
            'status' => 'error'
        ], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $usuario_id
     * @return \Illuminate\Http\Response
     */
    public function showProyectosByUsuario($usuario_id)
    {
        $proyectos = ProyectosUsuario::where(['usuario_id' => $usuario_id])
            ->with(['proyecto.actividades.actividadesRecursos.recurso', 'proyecto.actividades.planActividad.plan'])
            ->get()->toArray();

        $data = [];

        foreach ($proyectos as $proyecto) {
            foreach ($proyecto['actividades'] as $actividad) {
                
            }
        }

        if (count($proyectos) > 0)
            return response()->json([
                'message' => 'Consulta exitosa',
                'data' => $proyectos,
                'status' => 'ok'
            ], 200);

        return response()->json([
            'message' => 'No existen registros',
            'data' => [],
            'status' => 'error'
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyActividad($id)
    {
        $actividad = Actividad::where(['id' => $id]);
        
        if (!$actividad->exists())
            return response()->json([
                'message' => 'No existen registros de la actividad',
                'data' => [],
                'status' => 'error'
            ], 200);

        $planesActividades = $actividad->with(['planActividad.seguimientos'])->get()->toArray()[0];

        for ($i=0; $i < count($planesActividades['plan_actividad']); $i++) { 
            $seguimientos = $planesActividades['plan_actividad'][$i]['seguimientos'];
            
            if(count($seguimientos) > 0)
                return response()->json([
                    'message' => 'No es posible eliminar la actividad ya que tiene seguimientos iniciados',
                    'data' => [],
                    'status' => 'error'
                ], 200);
        }

        if ($actividad->delete())
            return response()->json([
                'message' => 'Actividad eliminada',
                'data' => [],
                'status' => 'ok'
            ], 200);
            
        return response()->json([
            'message' => 'Ocurrió un error',
            'data' => [],
            'status' => 'error'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $plan_actividad_id
     * @return \Illuminate\Http\Response
     */
    public function updateActividadPlanProyecto(Request $request , $plan_actividad_id){
        if (!$request->has('estado') or !$request->has('peso') or !$request->has('costo')){
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);
        }

        $planActividad = PlanActividad::where(['id' => $plan_actividad_id]);

        if(!$planActividad->exists())
            return response()->json([
                'message' => 'No existen registros de esa actividad',
                'data' => [],
                'status' => 'error'
            ], 200);

        $update = $planActividad->update([
            'peso' => $request->get('peso'), 
            'estado' => $request->get('estado'), 
            'costo' => $request->get('costo'), 
        ]);

        if(!$update)
            return response()->json([
                'message' => 'Ha ocurido un error',
                'data' => [],
                'status' => 'error'
            ], 200);

        $seguimientos = Seguimiento::where(['plan_actividad_id' => $plan_actividad_id, 'periodo_evaluado' => date('Y')]);
        $seguimientos->update(['estado' => $request->get('estado')]);

        return response()->json([
            'message' => 'Actualización exitosa',
            'data' => [],
            'status' => 'ok'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateActividad(Request $request, $id){
        if (!$request->has('indicador_id') or !$request->has('nombre') or !$request->has('descripcion') or
            !$request->has('fecha_inicio') or !$request->has('fecha_fin') or !$request->has('costo') or
            !$request->has('unidad_medida') or !$request->has('peso')){
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);
        }

        $indicador = Indicador::where(['id' => $request->get('indicador_id')])->exists();

        if(!$indicador)
            return response()->json([
                'message' => 'No existen registros del indicador',
                'data' => [],
                'status' => 'error'
            ], 200);

        $actividad = Actividad::where(['id' => $id]);

        if (count($actividad->get()->toArray()) == 0)
            return response()->json([
                'message' => 'No existen registros de la actividad',
                'data' => [],
                'status' => 'error'
            ], 200);

        $set = [
            'indicador_id' => $request->get('indicador_id'),
            'nombre' => $request->get('nombre'),
            'descripcion' => $request->get('descripcion'),
            'fecha_inicio' => $request->get('fecha_inicio'),
            'fecha_fin' => $request->get('fecha_fin'),
            'costo' => $request->get('costo'),
            'unidad_medida' => $request->get('unidad_medida'),
            'peso' => $request->get('peso')
        ];

        if (!$actividad->update($set))
            return response()->json([
                'message' => 'Ha ocurido un error',
                'data' => [],
                'status' => 'error'
            ], 200);

        return response()->json([
            'message' => 'Actualización exitosa',
            'data' => [],
            'status' => 'ok'
        ], 200);
    }

    /**
     * Metodo que permite eliminar un recurso que tenga asignada una actividad
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function eliminarActividadRecurso($id){
        if(ActividadRecurso::where(['id' => $id])->delete())
            return response()->json([
                'message' => 'Recurso eliminado',
                'data' => [],
                'status' => 'ok'
            ], 200);

        return response()->json([
            'message' => 'Ocurrio un error inesperado',
            'data' => [],
            'status' => 'ok'
        ], 200);
    }

    /**
     * Metodo que permite eliminar un usuario responsable que tenga asignada una actividad
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function eliminarResponsableProyecto($id){
        if(ProyectosUsuario::where(['id' => $id])->delete())
            return response()->json([
                'message' => 'Usuario desagregado',
                'data' => [],
                'status' => 'ok'
            ], 200);

        return response()->json([
            'message' => 'Ocurrio un error inesperado',
            'data' => [],
            'status' => 'ok'
        ], 200);
    }

    /**
     * Permite agregar uno o más recursos nuevos a una actividad en especifico
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function agregarRecursosActividad(Request $request){
        if(!$request->has('actividad_id') OR !$request->has('recursos'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $actividad = Actividad::where(['id' => $request->get('actividad_id')])->exists();

        if(!$actividad)
            return response()->json([
                'message' => 'No existe una actividad asociada a ese id',
                'data' => [],
                'status' => 'error'
            ], 200);

        foreach ($request->get('recursos') as $r){
            $recurso = Recurso::where(['id' => $r])->exists();

            if(!$recurso)
                return response()->json([
                    'message' => 'No existe un recursos asociado al id '.$r,
                    'data' => [],
                    'status' => 'error'
                ], 200);

            $exists = ActividadRecurso::where(['actividad_id' => $request->get('actividad_id'), 'recursos_id' => $r])->exists();

            if($exists)
                return response()->json([
                    'message' => 'El recurso ya está asignado a la actividad',
                    'data' => [],
                    'status' => 'error'
                ], 200);

            $actividadRecurso = new ActividadRecurso([
                'actividad_id' => $request->get('actividad_id'),
                'recursos_id' => $r
            ]);

            if(!$actividadRecurso->save())
                return response()->json([
                    'message' => 'Ha ocurido un error',
                    'data' => [],
                    'status' => 'error'
                ], 200);
        }

        return response()->json([
            'message' => 'Recurso asignado',
            'data' => [],
            'status' => 'ok'
        ], 201);
    }

    /**
     * Permite agregar uno o más usuarios reponsables a una actividad en especifico
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function agregarUsuarioResponsable(Request $request){
        if(!$request->has('proyecto_id') OR !$request->has('responsables'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $proyecto = Proyecto::where(['id' => $request->get('proyecto_id')]);

        if(!$proyecto->exists())
            return response()->json([
                'message' => 'No existe un proyecto asociado a ese id',
                'data' => [],
                'status' => 'error'
            ], 200);

        foreach ($request->get('responsables') as $r){
            $usuario = Usuario::where(['id' => $r]);

            if(!$usuario->exists())
                return response()->json([
                    'message' => 'No existe un usuario asociado al id '.$r,
                    'data' => [],
                    'status' => 'error'
                ], 200);

            $exists = ProyectosUsuario::where(['proyecto_id' => $request->get('proyecto_id'), 'usuario_id' => $r])->exists();

            if($exists)
                return response()->json([
                    'message' => 'El usuario ya está asignado al proyecto',
                    'data' => [],
                    'status' => 'error'
                ], 200);

            $proyectoUsuario = new ProyectosUsuario([
                'proyecto_id' => $request->get('proyecto_id'),
                'usuario_id' => $r
            ]);

            if(!$proyectoUsuario->save())
                return response()->json([
                    'message' => 'Ha ocurido un error',
                    'data' => [],
                    'status' => 'error'
                ], 200);

            // $usuario = $usuario->get()->toArray()[0];
            // $proyecto = $proyecto->with(['proyecto.planesProyectos.plan'])->get()->toArray()[0];

            // Mail::to($usuario['email'], 'SEPLAC UFPS')->send(new Responsable($usuario['email'], $proyecto));
        }

        return response()->json([
            'message' => 'Usuario asignado',
            'data' => [],
            'status' => 'ok'
        ], 201);
    }

    public function showObservationActividad($id){
        return response()->json([
            'message' => 'Consulta exitosa',
            'data' => Observacion::where(['id' => $id])->with(['actividad'])->get()->toArray(),
            'status' => 'ok'
        ], 200);
    }

}
