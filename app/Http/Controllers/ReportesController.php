<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlanProyecto;
use App\Models\PlanActividad;
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
        // $this->middleware('auth:api');
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

    private function unique_multidim_array($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();
       
        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }

    public function resumenPlanPeriodoProgramaRender($plan_id, $periodo, $render = true)
    {
        $actividadesPlan = PlanActividad::where(['plan_id' => $plan_id])
            ->with(['plan.programaAcademico', 'actividad.proyecto.programas.programa.linea.eje', 'seguimientos']);

        $actividadesPlan = $actividadesPlan->get()->toArray();
        $plan = $actividadesPlan[0]['plan'];
        $proyectos = [];

        foreach ($actividadesPlan as $planActividad) {
            array_push($proyectos, $planActividad['actividad']['proyecto']);
        }

        $proyectos = $this->unique_multidim_array($proyectos, 'id');

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

        foreach ($proyectos as $proyecto) {
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

            $data_proyecto['procentaje_avance'] = 0;

            foreach ($actividadesPlan as $actividad) {
                $seguimientos = $actividad['seguimientos'];

                foreach ($seguimientos as $seguimiento) {
                    if ($seguimiento['periodo_evaluado'] == $periodo && $seguimiento['estado'] == 'ACTIVO') {
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
        if (!$request->has('plan_id') or !$request->has('proyecto_id'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $data = $this->cargarResumenGeneralProyectoRender($request->get('plan_id'), $request->get('proyecto_id'), false);

        return response()->json([
            'message' => 'Reporte',
            'data' => $data,
            'status' => 'ok'
        ], 200);
    }

    public function cargarResumenGeneralProyectoRender($plan_id, $proyecto_id, $render = true)
    {

        $actividadesPlan = PlanActividad::where(['plan_id' => $plan_id])
            ->with(['actividad.proyecto.programas.programa.linea.eje' => function($query) use ($proyecto_id) {
                $query->where(['id' => $proyecto_id]);
            }, 'seguimientos'])->get()->toArray();

        $actividades = [];

        foreach ($actividadesPlan as $actividadPlan) {
            if($actividadPlan['actividad']['proyecto'] != null)
                array_push($actividades, $actividadPlan);
        }

        $proyecto = $actividades[0]['actividad']['proyecto'];
        $plan = Plan::where(['id' => $plan_id])->get()->toArray()[0];

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

        foreach ($actividades as $actividad) {
            $data_actividad = [
                'nombre' => $actividad['actividad']['nombre'],
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
