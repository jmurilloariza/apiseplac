<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlanProyecto;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class ReportesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function resumenPlanPeriodoPrograma(Request $request)
    {
        if (!$request->has('plan_id') or !$request->has('periodo'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $data = $this->resumenPlanPeriodoProgramaRender($request->get('plan_id'), $request->get('periodo'), false);

        return response()->json([
            'message' => 'Reporte',
            'data' => $data,
            'status' => 'ok'
        ], 200);
    }

    public function resumenPlanPeriodoProgramaRender($id, $periodo, $render = true)
    {
        $plan = Plan::where(['id' => $id])->with([
            'programaAcademico',
            'planesProyectos.proyecto.actividades.seguimientos',
            'planesProyectos.proyecto.programas.programa.linea.eje',
        ])->get()->toArray()[0];

        $data = [];
        $director = Usuario::where(['programa_academico_id' => $plan['programa_academico']['id'], 'rol_id' => 4])->get()->toArray()[0];

        $nombreDirector = $director['name'] ?? '';
        $nombreDirector .= ' ' . $director['apellidos'] ?? '';

        $data['plan']['programa_academico'] = $plan['programa_academico']['nombre'];
        $data['plan']['director'] = $nombreDirector;
        $data['plan']['nombre'] = $plan['nombre'];
        $data['plan']['vigencia'] = $plan['periodo_inicio'] . ' al ' . $plan['periodo_fin'];
        $data['plan']['periodo_evaluado'] = $periodo;

        $data['plan']['proyectos'] = [];

        $planesProyectos = $plan['planes_proyectos'];

        foreach ($planesProyectos as $planProyecto) {
            $proyecto = $planProyecto['proyecto'];
            $data_proyecto = [
                'nombre' => $proyecto['nombre'],
                'descripcion' => $proyecto['descripcion'],
            ];

            $data_proyecto['programas'] = [];
            $programas = $proyecto['programas'];

            foreach ($programas as $p) {
                $programa = $p['programa'];
                array_push($data_proyecto['programas'], $programa['nombre']);
                $data_proyecto['eje'] = $programa['linea']['eje']['nombre'];
                $data_proyecto['linea'] = $programa['linea']['nombre'];
            }

            $actividades = $proyecto['actividades'];
            $data_proyecto['procentaje_avance'] = 0;

            foreach ($actividades as $actividad) {
                $seguimientos = $actividad['seguimientos'];

                foreach ($seguimientos as $seguimiento) {
                    if ($seguimiento['periodo_evaluado'] == $periodo) {
                        $data_proyecto['procentaje_avance'] += intval($actividad['peso']) * intval($seguimiento['valoracion']);
                        break;
                    }
                }
            }

            $data_proyecto['procentaje_avance'] = $data_proyecto['procentaje_avance'] / 100;

            array_push($data['plan']['proyectos'], $data_proyecto);
        }

        if (!$render) return $data;

        $html = view('reports.resumenPlan')->with(['plan' => $data['plan']]);
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'LETTER',
            'orientation' => 'P'
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    public function cargarResumenGeneralProyecto(Request $request)
    {
        if (!$request->has('proyecto_plan_id'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $data = $this->cargarResumenGeneralProyectoRender($request->get('proyecto_plan_id'), false);

        return response()->json([
            'message' => 'Reporte',
            'data' => $data,
            'status' => 'ok'
        ], 200);
    }

    public function cargarResumenGeneralProyectoRender($proyecto_plan_id, $render = true)
    {
        $proyecto_plan = PlanProyecto::where(['id' => $proyecto_plan_id])
            ->with(['proyecto.programas.programa.linea.eje', 'proyecto.actividades.seguimientos', 'plan']);

        if (!$proyecto_plan->exists())
            return response()->json([
                'message' => 'No existe el proyecto',
                'data' => [],
                'status' => 'error'
            ], 200);

        $proyecto_plan = $proyecto_plan->get()->toArray()[0];

        $proyecto = $proyecto_plan['proyecto'];
        $plan = $proyecto_plan['plan'];

        $data = [
            'nombre' => $proyecto['nombre'],
            'descripcion' => $proyecto['descripcion'],
        ];

        $data['programas'] = [];
        $programas = $proyecto['programas'];

        foreach ($programas as $p) {
            $programa = $p['programa'];
            array_push($data['programas'], $programa['nombre']);
            $data['eje'] = $programa['linea']['eje']['nombre'];
            $data['linea'] = $programa['linea']['nombre'];
        }

        $data['plan']['vigencia'] = $plan['periodo_inicio'] . ' al ' . $plan['periodo_fin'];
        $data['plan']['nombre'] = $plan['nombre'];
        $data['porcentaje_cumplimiento'] = 0;

        $data['actividades'] = [];

        $actividades = $proyecto['actividades'];

        foreach ($actividades as $actividad) {
            $data_actividad = [
                'nombre' => $actividad['nombre'],
                'peso' => $actividad['peso']
            ];

            $data_actividad['seguimientos'] = [];
            $seguimientos = $actividad['seguimientos'];

            $valoracion = 0;

            foreach ($seguimientos as $seguimiento) {
                $data_seguimiento = [
                    'periodo' => $seguimiento['periodo_evaluado'],
                    'valoracion' => $seguimiento['valoracion'],
                ];

                $valoracion += intval($seguimiento['valoracion']);
                array_push($data_actividad['seguimientos'], $data_seguimiento);
            }

            if (count($seguimientos) > 0) {
                $data_actividad['promedio_avance'] = $valoracion / count($seguimientos);
                $data['porcentaje_cumplimiento'] += $valoracion / count($seguimientos) * $actividad['peso'];
            } else {
                $data['porcentaje_cumplimiento'] += 0;
                $data_actividad['promedio_avance'] = $valoracion;
            }
            array_push($data['actividades'], $data_actividad);
        }

        $data['porcentaje_cumplimiento'] = $data['porcentaje_cumplimiento'] / 100;

        if (!$render) return $data;

        $html = view('reports.resumenProyecto')->with(['proyecto' => $data]);
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'LEGAL',
            'orientation' => 'L'
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
}
