<!DOCTYPE html>
<html>
    <head>
        <title>Sistema de Control de Citas</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="librerias/js/jquery.1.10.js" type="text/javascript"></script> 
        <script src="librerias/script/login.js" type="text/javascript"></script> 
        <style type="text/css">

            div#logueo{
                position:fixed;
                top:50%;
                left:50%;
                margin-left:-290px;
                margin-top:-155px
            }

            table#login{
                width:580px;
                height:380px;
                background-image:url(logo.png); 
                background-repeat:no-repeat;
            }
            .tabla {
                position: absolute;
                left: 203px;
                top: 202px;
                height: 149px;
            }
        </style>
    </head>
    <body style="background-image:url(degradado.jpg);height:200px;">   
        <div id="logueo">
            <form name="frmlogin" autocomplete="off" id="frmlogin" method="POST" enctype="multipart/form-data">
              <table width="352" align="center" cellpadding="0" cellspacing="0" id="login">
              <tbody>
                <tr>
                  <td width="423">
                      <div class="tabla" style="width: 340px;">
                    <table width="234" border="0" style="margin-top: -25px; margin-left: 51px;">
                      <tbody>
                        <tr>
                          <td width="56" height="39" style="color:#881875;font-size: 12px;font-family:Arial">
                              Usuario:
                          </td>
                          <td width="168">
                              <input style="border:0;background-color:transparent;width:158px; border-radius: 50px 50px;" type="text" name="usuario" id="usuario" value="" /></td>
                        </tr>
                        <tr>
                          <td  height="32" style="color:#881875;font-size: 12px;font-family:Arial">
                              Clave:
                          </td>
                          <td>
                              <input style="border:0;background-color:transparent;width:158px; border-radius: 50px 50px;" type="password" name="clave" id="clave" value="" /></td>
                        </tr>
                        <tr>
                          <div id="cargando"></div>
                          <td colspan="2" align="center">
                              <span style="color:#FF0000;" id="error"></span></td>
                        </tr>
                        <tr>
                          <td  colspan="2" align="left" >
                              <input type="hidden" name="accion" id="accion"  value="Ingresar"/>
                            <img src="iniciar.png" alt="cajas" width="130" height="29" id="ingresar" style="margin-left:40px;cursor:pointer"></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  </td>
                </tr>
              </tbody>
              </table>
            </form>
        </div>
    </body>
</html>