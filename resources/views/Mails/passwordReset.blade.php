@extends('Mails.app')

@section('asunto')
    {{$asunto}}
@endsection

@section('contenido')
    <br>
    <p>Usted ha solicitado por medio del portal web SEPLAC el restablecimiento de su contraseña.</p>
    <br>
    <p>Ingrese en el siguiente enlace para continuar con esta operación. Cabe aclarar que este enlace solo será valido una vez y caducará en 24 horas desde su generación.</p>
    <br>
<p><a href="http://{{$host??env('HOST_CLIENT')}}/#/auth/login?k={{$k}}" target="_blank">Restablecer Clave</a></p>
@endsection