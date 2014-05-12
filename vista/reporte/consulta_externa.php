<?php
session_start();
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));

require '../../librerias/globales.php';
require_once '../../modelo/medico/Medico.php';
require_once '../../modelo/mantenimientos/Consultorio.php';

$obj_medico = new Medico();
$obj_cons   = new Consultorio();
if (isset($_GET['modulo'])) {
    $obj_medico->url($_SERVER['SCRIPT_NAME'], $_GET['modulo']);
}

$consul_result = $obj_cons->getConsultorioAll();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap_theme; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_estilos; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_select2; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_select2_bootstrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap_datepicker; ?>"/>

        <script src="<?php echo _ruta_librerias_js . _js_jquery; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_dataTable; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2_es; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_datepicker; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_datepicker_es; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_librerias; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_validarcampos; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_script_js . 'reporte_consulta_ex.js' ?>" type="text/javascript"></script>

    </head>
    <body>
        <div class="panel panel-default" style="width : 90%;margin: auto;height: auto;position: relative; top:25px;">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Reporte  de Morbilidad diaria consulta externa</div>
            <div class="panel-body">
                <form name="frmasignar" id="frmasignar" method="post" enctype="multipart/form-data">
                    <table width="649" height="322" align="center">
                        <tr>
                            <td width="85" height="21" align="left">&nbsp;</td>
                            <td height="21" colspan="5" align="left"><label class="ccontrol-label">Por Fecha</label></td>
                        </tr>
                        <tr>
                            <td height="40" align="left">Consultorio:</td>
                            <td width="211"><div id="div_especialidad" style="margin-top: 10px;" class="form-group">
                                    <select id="consultorio" name="consultorio" class="form-control input-sm select2">
                                        <option value="0">Seleccione</option>
                                        <?php
                                        for ($i = 0; $i < count($consul_result); $i++) {
                                            ?>
                                            <option value="<?php echo $consul_result[$i]['num_consultorio'] ?>"><?php echo $consul_result[$i]['consultorio'] ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </div></td>
                            <td width="31">
                                <img style="cursor: pointer" id="imgconsultorio" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                            </td>
                            <td width="63" height="40" align="left">Fecha:</td>
                            <td width="214">
                                <div style="margin-top: 10px" class="form-group">
                                    <input style="background-color: #FFFFFF;" readonly type="text" class="form-control input-sm" id="fecha" name="fecha"  maxlength="10" />
                                </div>
                            </td>
                            <td width="17" height="40" align="left">
                                <img style="cursor: pointer" id="imgconsultorio2" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/></td>
                        </tr>
                        <tr>
                            <td height="42" align="left">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td height="42" align="left">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td height="42" align="left">&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="22" align="left">&nbsp;</td>
                            <td>Datos del Medico</td>
                            <td>&nbsp;</td>
                            <td height="22" align="left">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td height="22" align="left">&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="40" align="left">C&eacute;dula:</td>
                            <td>
                                <div id="div_cedula_pm" class="input-group">
                                    <div class="input-group-btn">
                                        <button style="font-size: 11px;" id="btn_nac_m" type="button" class="btn btn-default dropdown-toggle input-sm" data-toggle="dropdown">N <span class="caret"></span></button>
                                        <ul id="nacionalidad_m" class="dropdown-menu">
                                            <li><span id="N">N</span></li>
                                            <li><span id="V">V</span></li>
                                            <li><span id="E">E</span></li>
                                        </ul>
                                    </div>
                                    <input type="hidden" id="hnac_m" name="hnac_m" />
                                    <input type="text" class="form-control input-sm" id="cedula_pm" name="cedula_pm"  value="" maxlength="10" />
                                    <span class="input-group-btn ">
                                        <input style="font-size: 11px;" id="btnbuscar_m" type="button" class="btn btn-primary input-sm" value="Buscar"/>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <img style="cursor: pointer" id="imgconsultorio3" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                            </td>
                            <td height="40" align="left">Nombres:</td>
                            <td>
                                <div style="margin-top: 10px" class="form-group">
                                    <input disabled="disabled" readonly type="text" class="form-control input-sm" id="nombre" name="nombre"  />
                                </div>
                            </td>
                            <td height="40" align="left">&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="53"  colspan="6" align="center">
                                <!--<div class="btn-group" data-toggle="buttons" >
                                    <label id="l_hoy" class="btn btn-primary  btn-sm">
                                        <input type="checkbox" name="hoy" id="hoy"  value="1" />
                                        <span  id="sp_hoy">HOY</span> 
                                    </label>
                                </div>-->
                            </td>
                        </tr>
                        <tr>
                            <td height="26"  colspan="6" align="center">
                                <div id="botones">
                                    <input disabled="disabled" class="btn btn-default btn-sm" id="btnaccion" name="btnaccion" type="button" value="Ver"  />
                                    <input class="btn btn-default btn-sm" id="btnlimpiar" name="btnlimpiar" type="button" value="Limpiar" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td height="30"  colspan="6" align="center">&nbsp;</td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </body>
</html>