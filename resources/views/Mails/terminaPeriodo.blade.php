@extends('Mails.app')

@section('asunto')
    {{$asunto}}
@endsection

@section('contenido')
    <br>
    <p>
        Se ha terminado el proceso de seguimiento del proyecto <strong>{{$data['nombre_proyecto']??'Este es el nombre del proyecto'}}</strong> 
        para el periodo <strong>{{$data['periodo']??'2019-II'}}</strong>.
    </p>
    <br>
@endsection