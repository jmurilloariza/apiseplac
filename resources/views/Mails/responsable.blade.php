@extends('mails.app')

@section('asunto')
    {{$asunto}}
@endsection

@section('contenido')
    <br>
    <p>
        Ha sido designado como responsable de la actividad <strong>{{$actividad['nombre']??'Este es el nombre de la actividad'}}</strong>. La actividad corresponde al 
        proyecto <strong>{{$proyecto['nombre']??'Este es el nombre del proyecto'}}</strong>  y hace parte del plan de acción que se lleva acabo desde el 
        periodo <strong>{{$plan['periodo_inicio']??'2019-I'}}</strong> al <strong>{{$plan['periodo_fin']??'2019-II'}}</strong>.
    </p>
    <br>
@endsection