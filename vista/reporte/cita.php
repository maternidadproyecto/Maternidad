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
        <script src="<?php echo _ruta_librerias_script_js . 'reporte_citas.js' ?>" type="text/javascript"></script>

    </head>
    <body>
        <div class="panel panel-default" style="width : 90%;margin: auto;height: auto;position: relative; top:25px;">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Reporte  de Citas</div>
            <div class="panel-body">
                <form name="frmasignar" id="frmasignar" method="post" enctype="multipart/form-data">
                    <table width="783" height="234" align="center">
                        <tr>
                            <td width="26" height="21" align="left">&nbsp;</td>
                            <td height="21" colspan="5" align="left"><label class="ccontrol-label">Por Fecha</label></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="26" height="40" align="left">&nbsp;</td>
                            <td width="79" height="40" align="left">Desde:</td>
                            <td width="233">
                                <div style="margin-top: 10px" class="form-group">
                                    <input style="background-color: #FFFFFF;" readonly="readonly" type="text" class="form-control input-sm" id="desde" name="desde"  maxlength="10" />
                                </div>
                            </td>
                            <td width="68">&nbsp;</td>
                            <td width="48" height="40" align="left">Hasta:</td>
                            <td width="247">
                                <div style="margin-top: 10px" class="form-group">
                                    <input style="background-color: #FFFFFF;" readonly="readonly" type="text" class="form-control input-sm" id="hasta" name="hasta" maxlength="10" />
                                </div>
                            </td>
                            <td width="50">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="26" height="40" align="left">&nbsp;</td>
                            <td width="79" height="40" align="left">Consultorio:</td>
                            <td>
                                <div id="div_especialidad" style="margin-top: 10px;" class="form-group">
                                    <select id="consultorio" name="consultorio" class="form-control input-sm select2">
                                        <option value="0">Seleccione</option>
                                        <?php
                                        for ($i = 0; $i < count($consul_result); $i++) {
                                            ?>
                                            <option value="<?php echo $consul_result[$i]['num_consultorio'] ?>"><?php echo $consul_result[$i]['consultorio'] ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </div>
                            </td>
                            <td width="68">
                                <img style="cursor: pointer" id="imgconsultorio" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                            </td>
                            <td width="48" height="40" align="left">Medico:</td>
                            <td width="247">
                                <div id="div_fecha" style="margin-top: 10px" class="form-group">
                                    <span class="form-group" style="margin-top: 10px">
                                        <input  type="text" class="form-control input-sm" id="medico" name="medico"  maxlength="50" />
                                    </span>
                                </div>
                            </td>
                            <td width="50">
                                <img style="cursor: pointer" id="imgfechacita" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                            </td>
                        </tr>
                        <tr>
                            <td height="53"  colspan="7" align="center">
                                <div class="btn-group" data-toggle="buttons" >
                                    <label id="l_hoy" class="btn btn-primary  btn-sm">
                                        <input type="checkbox" name="hoy" id="hoy"  value="1" />
                                        <span  id="sp_hoy">HOY</span> 
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td height="26"  colspan="7" align="center">
                                <div id="botones">
                                    <input disabled="disabled" class="btn btn-default btn-sm" id="btnaccion" name="btnaccion" type="button" value="Ver"  />
                                    <input class="btn btn-default btn-sm" id="btnlimpiar" name="btnlimpiar" type="button" value="Limpiar" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td height="30"  colspan="7" align="center">&nbsp;</td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </body>
</html>