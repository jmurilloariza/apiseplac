@extends('Mails.app')

@section('asunto')
    {{$asunto}}
@endsection

@section('contenido')
    <br>
    <p>
        Ha sido designado como responsable del proyecto <strong>{{$proyecto['nombre']??'Este es el nombre del proyecto'}}</strong> el cual 
        hace parte del plan de acción que se lleva acabo desde el periodo <strong>{{$plan['periodo_inicio']??'2019-I'}}</strong> al 
        <strong>{{$plan['periodo_fin']??'2019-II'}}</strong>.
    </p>
    <br>
@endsection