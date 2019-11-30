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

Route::get('/', function () {
    return view('reports.resumenPlan');
});

Route::get('mail', function () {
    return view('Mails.passwordReset')->with([
        'k' => '02',
        'asunto' => 'Restablecimiento de clave personal'
    ]);
});
