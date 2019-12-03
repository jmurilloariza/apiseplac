<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="author" content="jmurilloariza - Jeferson Murillo Ariza">
    <title>Resumen de proyecto</title>
    <link rel="stylesheet" href="reports/css/reportes.css" media="all" />
  </head>
  <body>
    <main>
        <div id="invoice">
          <h1>{{strtoupper($proyecto['nombre'])}}</h1>
          <div class="date">Resumen general de proyecto</div>
          <div class="date">Fecha reporte: {{date('Y-m-d')}}</div>
        </div>

        <div class="descripcion">
          <h2>Nombre: <span class="contentText">{{strtoupper($proyecto['nombre'])}}</span></h2>
          <h2>Descripcion: <span class="contentText">{{$proyecto['descripcion']}}</span></h2> 
          <h2>Eje estrategico: <span class="contentText">{{$proyecto['eje']}}</span></h2> 
          <h2>Linea: <span class="contentText">{{$proyecto['linea']}}</span></h2> 
          <h2>Programas: 
            @php $nombreProgramas = ''; @endphp
            @foreach ($proyecto['programas'] as $programa)
                @php $nombreProgramas .= $programa.'-'; @endphp
            @endforeach
            <span class="contentText">{{$nombreProgramas}}</span>
          </h2> 
          <h2>Procentaje de cumplimiento: <span class="contentText">{{$proyecto['porcentaje_cumplimiento']}}%</span></h2> 
        </div>

      </div>
        <h2>ACTIVIDADES</h2>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th class="desc">Nombre</th>
            <th class="desc">Peso</th>
            @foreach ($proyecto['actividades'][0]['seguimientos'] as $a)
                <th class="desc">Periodo</th>
                <th class="desc">Valoración</th>
            @endforeach
          </tr>
        </thead>
        <tbody>

        @foreach ($proyecto['actividades'] as $actividad)
          <tr>
            <td class="desc">{{$actividad['nombre']}}</td> 
            <td class="desc">{{$actividad['peso']}}</td> 
            @foreach ($actividad['seguimientos'] as $seguimiento)
                <td class="desc">{{$seguimiento['periodo']}}</td> 
                <td class="desc">{{$seguimiento['valoracion']}}</td> 
            @endforeach
            {{-- <td><h3>{{$proyecto['procentaje_cumplimiento']}}%</h3></td> --}}
          </tr>

          @endforeach
        </tbody>
      </table>
      {{-- <div id="thanks">Thank you!</div> --}}
      <div id="notices">
        <div>SEPLAC UFPS - {{date('Y')}}</div>
        <div class="notice">Avenida Gran Colombia No. 12E-96 Barrio Colsag, San José de Cúcuta - Colombia</div>
      </div>
    </main>
  </body>
</html>