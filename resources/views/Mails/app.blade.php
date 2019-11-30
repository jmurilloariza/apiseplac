<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Laravel</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
  <!-- Styles -->
  <style>
    html,
    body {
      background-color: #fff;
      color: #636b6f;
      font-family: 'Roboto', sans-serif;
      height: 100vh;
      margin: 0;
    }
  </style>
</head>

<body>
  <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#8d8e90" style="padding:50px 0;background:url('http://divisist2.ufps.edu.co/assets/email/images/grid.png')">
    <tbody>
      <tr>
        <td>
          <table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center">
            <tbody>
              <tr>
                <td>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="215">
                          <a href="http://{{$host??env('HOST_CLIENT')}}/#" target="_blank">
                            <img style="padding-left:10px" src="http://divisist2.ufps.edu.co/assets/email/images/ufps_logo_205.jpg" width="205" height="52" border="0" alt="" class="CToWUd">
                          </a>
                        </td>
                        <td width="383">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                              <tr>
                                <td height="46" align="right" valign="middle">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tbody>
                                      <tr>
                                        <td width="96%" align="right">
                                          <font style="font-family:'Myriad Pro',Helvetica,Arial,sans-serif;color:#68696a;font-size:20px;text-transform:uppercase"><a href="http:// " style="color:#68696a;text-decoration:none" target="_blank"><strong><span class="il"></span> </strong></a></font>
                                        </td>
                                        <td width="4%">&nbsp;</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <td height="30"></td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              <tr>
                <td align="center" valign="middle">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="2%">&nbsp;</td>
                        <td width="96%" align="left" style="border-top:1px solid #000000" height="50">
                          <font style="font-family:'Myriad Pro',Helvetica,Arial,sans-serif;color:#68696a;font-size:18px">
                            <strong>
                              @yield('asunto')
                            </strong>
                          </font>
                        </td>
                        <td width="1%">&nbsp;</td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              <tr>
                <td>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="2%">&nbsp;</td>
                        <td width="96%" align="justify" valign="middle" style="font-family:Verdana,Geneva,sans-serif;color:#68696a;font-size:12px;line-height:16px">
                          @yield('contenido')
                          <p style="margin-bottom:0px"><b>Por favor no responder este correo.</b></p>
                          <p style="margin-bottom:0px">Cordialmente,</p>
                          <p style="margin-bottom:0px">Seplac UFPS</p>
                        </td>
                        <td width="1%">&nbsp;</td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><img src="https://ci6.googleusercontent.com/proxy/uBkmZXc0KZDiGeT5meVHzY_Vip7TH8hWBSQuS7ird01JmEZDymtQRfSyLx_CqT6cw4aabRCA2nMsn92YeOIoOBD_i6MJDF4DzIGaMKSyrb0muMDLWnPt=s0-d-e1-ft#http://divisist2.ufps.edu.co/assets/email/images/PROMO-GREEN2_07.jpg" width="598" height="7" style="display:block" border="0" alt="" class="CToWUd"></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="50%" align="right">
                          <font style="font-family:'Myriad Pro',Helvetica,Arial,sans-serif;color:#231f20;font-size:10px">
                            <strong>Avenida Gran Colombia No. 12E-96 Barrio Colsag, San José de Cúcuta - Colombia<br> Teléfono (057)(7) 5776655</strong></font>
                        </td>
                        <td width="4%" align="right"><a href="" target="_blank"><img src="https://ci6.googleusercontent.com/proxy/XSP0ZcTuqM3wNMVMDBY4FnN1clBAwyJH7b43kWaXCGUNIwIOCw3TeOMoxMHTUSMnbSZkVnwOR1IWzm8I6sAa_zRWrOdip27dmao=s0-d-e1-ft#http://divisist2.ufps.edu.co/assets/email/images/fb.png" alt="facebook" width="30" height="30" border="0" class="CToWUd"></a></td>
                        <td width="5%" align="center"><a href="" target="_blank"><img src="https://ci5.googleusercontent.com/proxy/NbA3Dy9g-vyoNQXFaKrNzyNyzfx2NB1yqdZuI-Vxpc6w82vMTamGpCAB1i9PzJYiaLbrsbHduU5uIIan6CgI3rVgcPvqJF7ChJs=s0-d-e1-ft#http://divisist2.ufps.edu.co/assets/email/images/tw.png" alt="twitter" width="30" height="30" border="0" class="CToWUd"></a></td>
                        <td width="4%" align="left"><a href="" target="_blank" alt="linkedin" width="30" height="30" border="0" class="CToWUd"></a></td>
                        <td width="5%">&nbsp;</td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
</body>

</html>