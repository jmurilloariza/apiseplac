<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="author" content="jmurilloariza - Jeferson Murillo Ariza">
    <title>Resumen plan {{strtoupper($plan['periodo_evaluado'])}}</title>
    <link rel="stylesheet" href="reports/css/reportes.css" media="all" />
  </head>
  <body>
    <main>
        <div id="invoice">
          <div class="date">Reporte por eje estratégico</div>
          <h1>{{strtoupper($plan['nombre'])}} {{strtoupper($plan['periodo_evaluado'])}}</h1>
          <div class="date">Fecha reporte: {{date('Y-m-d')}}</div>
          <div class="date">Periodo evaluado: {{strtoupper($plan['periodo_evaluado'])}}</div>
        </div>

        <div class="descripcion">
          <h2>Eje Estratégico: <span class="contentText"><strong>{{$plan['proyectos'][0]['eje']??'Ingenieria de sistemas'}}</strong></span></h2>
          <h2>Porcentaje de cumplimiento: <span class="contentText"><strong>{{$plan['porcentaje']??'0'}}%<strong></span></h2>
        </div>

        <br>

        <div class="descripcion">
          <h2>Programa académico: <span class="contentText">{{$plan['programa_academico']??'Ingenieria de sistemas'}}</span></h2>
          <h2>Director del programa: <span class="contentText">{{$plan['director']??'Pilar Rodriguez'}}</span></h2> 
          <h2>Vigencia: <span class="contentText">{{$plan['vigencia']??'2019-I al 2019-II'}}</span></h2> 
          <h2>Periodo evaluado: <span class="contentText">{{$plan['periodo_evaluado']??'2019-I'}}</span></h2> 
        </div>

      </div>

      <br>

      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th class="desc">PROYECTOS</th>
            <th class="total"></th>
          </tr>
        </thead>
        <tbody>

          @foreach ($plan['proyectos'] as $proyecto)
          <tr>
            <td class="desc">
              <h3>Nombre: <span>{{strtoupper($proyecto['nombre'])}}</span></h3> 
              <h3>Descripcion: <span>{{$proyecto['descripcion']}}</span></h3>
              <h3>Eje estrategico: <span>{{$proyecto['eje']}}</span></h3>
              <h3>Linea: <span>{{$proyecto['linea']}}</span></h3>
              <h3>Programas: 
                  @php $nombreProgramas = ''; @endphp

                  @foreach ($proyecto['programas'] as $programa)
                    @php $nombreProgramas .= $programa.'-' @endphp
                  @endforeach
    
                  <span>{{$nombreProgramas}}</span>
              </h3>
              
            </td>
            <td><h3>{{$proyecto['procentaje_avance']}}%</h3></td>
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