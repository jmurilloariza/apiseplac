<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Mpdf\Mpdf;

Route::get('/', function () {
    // $html = view('reports.resumenPlan');
    // $mpdf = new Mpdf([
    //     'mode' => 'utf-8',
    //     'format' => 'LETTER'
    // ]);
    // $mpdf->WriteHTML($html);
    // $mpdf->Output();
    return view('reports.resumenPlan');
});

Route::get('mail', function () {
    return view('mails.responsable')->with([
        'k' => '02',
        'asunto' => 'Restablecimiento de clave personal',
        'mensaje' => 'Ha sido designado como responsable de la actividad'
    ]);
});
