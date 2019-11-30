<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class ReportesController extends Controller
{
    public function resumenPlanPeriodoPrograma(Request $request)
    {
        if (!$request->has('plan_id') or !$request->has('periodo'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'error'
            ], 200);

        $data = $this->resumenPlanPeriodoProgramaRender($request->get('plan_id'),$request->get('periodo'), $request, false);

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
            'format' => 'LETTER'
        ]);
        
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
}
