<?php
error_reporting(0);
session_start();
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));


require_once '../../librerias/globales.php';
/*require_once '../../modelo/Seguridad.php';
$seguridad = new Seguridad();
if (isset($_GET['modulo'])) {
    $seguridad->url($_SERVER['SCRIPT_FILENAME'], $_GET['modulo']);
}*/


?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_estilos; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap_switch; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap_datepicker; ?>"/>

        <script src="<?php echo _ruta_librerias_js . _js_jquery; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_switch; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_datepicker; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_datepicker_es; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_dataTable; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_tooltip; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_validarcampos; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_script_js . 'historiaparto.js' ?>" type="text/javascript"></script>


    </head>
    <body>
        <div class="panel panel-default" style="width : 90%;margin: auto;height: auto;position: relative; top:25px;">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Historia Parto</div>
            <div class="panel-body">
                <table width="742" border="0" align="center">
                    <tr>
                        <td width="736" align="center">
                            <form name="frmespecialidad" id="frmespecialidad" method="post" enctype="multipart/form-data">
                                <table width="732" align="center">
                                    <tr>
                                        <td width="87" height="40" align="left">Cedula:</td>
                                        <td width="230" colspan="-2">
                                            <div  id="div_cedula" class="input-group">
                                                <div class="input-group-btn">
                                                    <button style="font-size: 11px;" id="btn_nac" type="button" class="btn btn-default dropdown-toggle input-sm" data-toggle="dropdown">N
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul id="nacionalidad" class="dropdown-menu">
                                                        <li><span id="N">NACIONALIDAD</span></li>
                                                        <li><span id="V">VENEZOLANO</span></li>
                                                        <li><span id="E">EXTRANJERO</span></li>
                                                    </ul>
                                                </div>
                                                <input type="hidden" id="hnac" name="hnac" />
                                                <input readonly id="text_nac" style="font-size: 11px;position: relative;top:8px;width: 40px;height:29px;background-color: transparent;border: none;padding-left: 2px;" maxlength="2"/>
                                                <input type="text" class="form-control input-sm" id="cedula_p" name="cedula_p"  value="" maxlength="9" style="margin-top:-30px;padding-left: 13px;"/>
                                                <span class="input-group-btn ">
                                                    <input id="btnbuscar" type="button" class="btn btn-primary input-sm" value="Buscar"/>
                                                </span>
                                            </div>
                                        </td>
                                        <td width="71">
                                            <img style="cursor: pointer" id="imgsector1" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td width="64" height="40" align="left">Historia:</td>
                                        <td width="229">
                                            <div style="margin-top: 10px;" class="form-group">
                                                <input type="text" class="form-control input-sm" id="historia" name="historia" value="" disabled="disabled" maxlength="20"/>
                                            </div>
                                        </td>
                                        <td width="23">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td width="87" height="40" align="left">Nombre:</td>
                                        <td width="230" colspan="-2">
                                            <div style="margin-top: 10px;" class="form-group">
                                                <input type="text" class="form-control input-sm" id="nombre" name="nombre" value="" disabled="disabled" maxlength="20"/>
                                            </div>
                                        </td>
                                        <td width="71">&nbsp;</td>
                                        <td width="64" height="40" align="left">Apellido:</td>
                                        <td width="229">
                                            <div style="margin-top: 10px;" class="form-group">
                                                <input type="text" class="form-control input-sm" id="apellido" name="apellido" value="" disabled="disabled" maxlength="20"/>
                                            </div>
                                        </td>
                                        <td width="23">&nbsp;</td>
                                    </tr>
                                    <tr>
                                   
                                        <td height="36" colspan="6">
                                            <fieldset style="margin-top:20px;">
                                                <legend style="font-size: 11px">
                                                    Datos del Hijo
                                                </legend>
                                            </fieldset>
                                            <!--<span style="text-decoration: underline">Datos del Hijo</span>-->
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="87" height="40" align="left">Fecha Nac:</td>
                                        <td colspan="-2">
                                            <div style="margin-top: 10px" class="form-group">
                                                <input  style="width: 240px;" type="text" class="form-control input-sm" id="fecha_nacimiento" name="fecha_nacimiento" value="" maxlength="10"/>
                                            </div>
                                        </td>
                                        <td width="71">
                                            <img style="cursor: pointer" id="imgsector3" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td width="64" height="40" align="left">Hora Nac:</td>
                                        <td>
                                            <div style="margin-top: 10px;" class="form-group">
                                                <input type="text" class="form-control input-sm" id="hora_nacimiento" name="fecha_nacimiento" value="" maxlength="20"/>
                                            </div>
                                        </td>
                                        <td>
                                            <img style="cursor: pointer" id="imgsector2" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="87" height="44" align="left">Sexo: </td>
                                        <td colspan="-2">
                                            <div style="width:240px;" id="swestatus" class="make-switch switch-small" data-on-label="Macuino" data-off-label="Femenino" data-on="primary" data-off="danger">
                                                <input type="checkbox">
                                                <input type="hidden" id="estatus" name="estatus" value="TRUE" />
                                            </div>
                                        </td>
                                        <td width="71">
                                            <img style="cursor: pointer" id="imgsector4" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td width="64" height="44" align="left">Peso: </td>
                                        <td>
                                            <div style="margin-top: 10px;" class="form-group">
                                                <input type="text" class="form-control input-sm" id="peso" name="peso" value="" maxlength="20"/>
                                            </div>
                                        </td>
                                        <td width="23">
                                            <img style="cursor: pointer" id="imgsector6" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="87" height="40" align="left">Talla:</td>
                                        <td colspan="-2">
                                            <div style="margin-top: 10px;" class="form-group">
                                                <input type="text" class="form-control input-sm" id="tamano" name="tamano" value="" maxlength="20"/>
                                            </div>
                                        </td>
                                        <td width="71">
                                            <img style="cursor: pointer" id="imgsector5" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td width="87" height="40" align="left">Observación:</td>
                                        <td>
                                            <textarea class="form-control input-sm" name="observacion" id="observacion" cols="40" rows="2"></textarea>
                                        </td>
                                        <td width="71">
                                            <img style="cursor: pointer" id="imgsector7" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  colspan="6" align="right">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td  colspan="6" align="center">
                                            <div id="botones">
                                                <input class="btn btn-default btn-sm" id="btnaccion" name="btnaccion" type="button" value="Agregar" />
                                                <input class="btn btn-default btn-sm" id="btnlimpiar" name="btnlimpiar" type="button" value="Limpiar" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                      <td  colspan="6" align="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td  colspan="6" align="center">
                                      <table style="width:100%" border="0" align="center" cellspacing="1" class="dataTable" id="tabla">
                                        <thead>
                                          <tr>
                                            <th>Fec. Nac.</th>
                                            <th>Hora Nac.</th>
                                            <th>Sexo</th>
                                            <th>Tamaño</th>
                                            <th>Peso</th>
                                            <th>Consultar</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>12/06/2013</td>
                                            <td>03:25 PM</td>
                                            <td>Femenino</td>
                                            <td>52 cm</td>
                                            <td>2,5 Kilos</td>
                                            <td>
                                                <img src="../../imagenes/sistema/consultar.png" style="width:25px; height:25px" alt="consultar"/>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table></td>
                                    </tr>
                                </table>
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>