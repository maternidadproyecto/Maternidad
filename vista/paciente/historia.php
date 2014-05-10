<?php
session_start();
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));

require_once '../../librerias/globales.php';
require_once '../../modelo/mantenimientos/Consultorio.php';

require_once '../../modelo/paciente/Historia.php';

$objcons = new Consultorio();
$objmod  = new Historia();

if (isset($_GET['modulo'])) {
    $objmod->url($_SERVER['SCRIPT_FILENAME'], $_GET['modulo']);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap_theme; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_select2; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_select2_bootstrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap_datepicker; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_estilos; ?>"/>

        <script src="<?php echo _ruta_librerias_js . _js_jquery; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_tooltip; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2_es; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_dataTable; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_datepicker; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_datepicker_es; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_tab; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_librerias; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_validarcampos; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_script_js . 'historia.js' ?>" type="text/javascript"></script>
        <style type="text/css">
            .tab-content {
                border-left: 1px solid #ddd;
                border-right: 1px solid #ddd;
                border-bottom: 1px solid #ddd;
                padding: 10px;
            }

            .nav-tabs {
                margin-bottom: 0;
            }
        </style>
    </head>
    <body>
        <div class="panel panel-default" style="width : 90%;margin: auto;height: auto;position: relative; top:25px;">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Historia Medica</div>
            <div class="panel-body">
                <table style="width: 100%" border="0" align="center">
                    <tr>
                        <td style="width: 100%" align="center">
                            <form name="frmhistoria" id="frmhistoria" method="post" enctype="multipart/form-data">
                                <table style="width:95%" border="0">
                                    <tr>
                                        <td width="8%" height="34" align="left">Cedula:</td>
                                        <td width="39%">
                                            <div id="div_cedula" class="input-group">
                                                <div class="input-group-btn">
                                                    <button style="font-size: 11px;" id="btn_nac" type="button" class="btn btn-default dropdown-toggle input-sm" data-toggle="dropdown">N 
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul id="nacionalidad" class="dropdown-menu">
                                                        <li><span id="N">N</span></li>
                                                        <li><span id="V">V</span></li>
                                                        <li><span id="E">E</span></li>
                                                    </ul>
                                                </div>
                                                <input type="hidden" id="hnac" name="hnac" />
                                                <input type="text" class="form-control input-sm" id="cedula_p" name="cedula_p"  value="" maxlength="9" />
                                                <span class="input-group-btn ">
                                                    <input style="font-size: 11px;" id="btnbuscar" type="button" class="btn btn-primary input-sm" value="Buscar"/>
                                                </span>
                                            </div>
                                        </td>
                                        <td width="8%">
                                            <img style="cursor: pointer" id="imgcedula" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td width="8%" height="34" align="left">Historia:</td>
                                        <td width="35%">
                                            <div style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="historia" name="historia" value="" disabled="disabled" maxlength="20"/>
                                            </div>
                                        </td>
                                        <td width="2%">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td height="34" align="left">Nombre:</td>
                                        <td>
                                            <div style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="nombre" name="nombre" value="" disabled="disabled" maxlength="20" />
                                            </div>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td height="34" align="left">Apellido:</td>
                                        <td>
                                        <div style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="apellido" name="apellido" value="" disabled="disabled" maxlength="20"/>
                                            </div>
                                            </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td height="34" align="left">Fech. Naci.:</td>
                                        <td>
                                            <div style="margin-top: 10px" class="form-group">
                                                <input   type="text" class="form-control input-sm" id="fecha_nacimiento" name="fecha_nacimiento" value="" disabled="disabled" maxlength="20"/>
                                            </div>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td height="34" align="left">Edad:</td>
                                        <td>
                                            <div style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="edad" name="edad" value="" disabled="disabled" maxlength="20"/>
                                            </div>
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>Ultima Cita:</td>
                                        <td>
                                            <div style="margin-top: 10px" class="form-group">
                                                <input   type="text" class="form-control input-sm" id="fecha_cita" name="fecha_cita" value="" disabled="disabled" maxlength="20"/>
                                            </div>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td height="34"> Consultorio: </td>
                                        <td>
                                            <div id="div_num_consultorio" style="margin-top: 10px;" class="form-group ">
                                                <select  name="num_consultorio" id="num_consultorio" class="form-control select2">
                                                    <option value="0">Seleccione</option>
                                                    <?php
                                                    $result_cons = $objcons->getConsultorioAll();
                                                    for ($i = 0; $i < count($result_cons); $i++) {
                                                        ?>
                                                        <option value="<?php echo $result_cons[$i]['num_consultorio'] ?>"><?php echo $result_cons[$i]['consultorio'] ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <img style="cursor: pointer" id="imgcedula2" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">Datos del M&eacute;dico</td>
                                    </tr>
                                    <tr>
                                        <td height="34"  align="left">C&eacute;dula</td>
                                        <td height="34"  align="left"><div id="div_cedula_pm" class="input-group">
                                                <div class="input-group-btn">
                                                    <button style="font-size: 11px;" id="btn_nac_m" type="button" class="btn btn-default dropdown-toggle input-sm" data-toggle="dropdown">N <span class="caret"></span></button>
                                                    <ul id="nacionalidad_m" class="dropdown-menu">
                                                        <li><span id="N">N</span></li>
                                                        <li><span id="V">V</span></li>
                                                        <li><span id="E">E</span></li>
                                                    </ul>
                                                </div>
                                                <input type="hidden" id="hnac_m" name="hnac_m" />
                                                <input type="text" class="form-control input-sm" id="cedula_pm" name="cedula_pm"  value="" maxlength="9" />
                                                <span class="input-group-btn ">
                                                    <input style="font-size: 11px;" id="btnbuscar_m" type="button" class="btn btn-primary input-sm" value="Buscar"/>
                                                </span>
                                            </div>
                                        </td>
                                        <td height="34"  align="left">
                                            <img style="cursor: pointer" id="imgcedula3" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td height="34"  align="left">Nombres:</td>
                                        <td height="34"  align="left">
                                            <div style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="nombre_m" name="nombre_m" value="" disabled="disabled" maxlength="20"/>
                                            </div>
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td height="53" colspan="6">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">
                                            <!-- -->
                                            <!-- Nav tabs -->
                                            <ul class="nav nav-tabs nav-justified" id="otros_dt">
                                                <li class="active"> 
                                                    <span id="s_medicos"  href="#medi" data-toggle="tab"> Datos Medicos </span> 
                                                </li>
                                                <li> 
                                                    <span id="observ_diag" href="#observacion" data-toggle="tab">Observaci&oacute;n y Diagn&oacute;stico</span> 
                                                </li>
                                            </ul>
                                            <!-- Tab panes -->
                                            <div class="tab-content">
                                                <div class="tab-pane fade in active" id="medi">
                                                    <table style="width:100%" align="center">
                                                        <tr>
                                                            <td width="64">&nbsp;&nbsp;Tama&ntilde;o: </td>
                                                            <td width="146">
                                                                <div id="div_tamano" style="margin-top: 10px" class="form-group">
                                                                    <input disabled="disabled" type="text" class="form-control input-sm" id="tamano" name="tamano" value="" maxlength="10"/>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <img style="cursor: pointer" id="imgsector4" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/></td>
                                                            <td width="45"> Peso: </td>
                                                            <td width="152">
                                                                <div id="div_peso" style="margin-top: 10px" class="form-group">
                                                                    <input disabled="disabled" type="text" class="form-control input-sm" id="peso" name="peso" value="" maxlength="30"/>
                                                                </div>
                                                            </td>
                                                            <td width="17">
                                                                <img  style="cursor: pointer" id="imgsector3" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                                            </td>
                                                            <td width="62">&nbsp;&nbsp;&nbsp;&nbsp;Tensi&oacute;n: </td>
                                                            <td width="144">
                                                                <div id="div_tesion" style="margin-top: 10px" class="form-group">
                                                                    <input disabled="disabled"  type="text" class="form-control input-sm" id="tension" name="tension" value="" maxlength="30"/>
                                                                </div>
                                                            </td>
                                                            <td width="16">
                                                                <img id="imgsector2" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>&nbsp;&nbsp;FUR: </td>
                                                            <td>
                                                                <div style="margin-top: 10px;" class="form-group">
                                                                    <input disabled="disabled"  type="text" class="form-control input-sm" id="fur" name="fur" value="" maxlength="10"/>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <img disabled="disabled" style="cursor: pointer" id="imgsector5" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                                            </td>
                                                            <td> FPP: </td>
                                                            <td>
                                                                <div style="margin-top: 10px;" class="form-group">
                                                                    <input disabled="disabled"   type="text" class="form-control input-sm" id="fpp" name="fpp" value="" maxlength="10"/>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <img disabled="disabled" style="cursor: pointer" id="imgsector" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                                            </td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="tab-pane fade" id="observacion">
                                                    <div class="form-inline"> Lugar de Control:&nbsp;&nbsp;
                                                        <div style="margin-top: 10px" class="form-group">
                                                            <textarea disabled="disabled" class="form-control" name="lugar_control" id="lugar_control" cols="80" rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-inline"> Diagn&oacute;stico:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <div style="margin-top: 10px" class="form-group">
                                                            <textarea disabled="disabled" class="form-control" name="diagnostico" id="diagnostico" cols="80" rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-inline"> Observaci&oacute;n:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <div style="margin-top: 10px" class="form-group">
                                                            <textarea disabled="disabled" class="form-control" name="observacion" id="observacion" cols="80" rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- -->
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" align="center">
                                            <div id="botones">
                                                <input class="btn btn-default btn-sm" id="btnaccion" name="btnaccion" type="button" value="Agregar" disabled="disabled" />
                                                <input class="btn btn-default btn-sm" id="btnlimpiar" name="btnlimpiar" type="button" value="Limpiar" />
                                                <input style="display: none" class="btn btn-primary btn-sm" id="btnver" name="btnver" type="button" value="Ver Historia" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">&nbsp;</td>
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