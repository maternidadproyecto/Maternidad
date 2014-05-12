<?php
session_start();
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));

require '../../librerias/globales.php';

require_once '../../modelo/cita/Asignar.php';
require_once '../../modelo/mantenimientos/Consultorio.php';

$objmod = new Asignar();

$obj_cons = new Consultorio();

if (isset($_GET['modulo'])) {
    $objmod->url($_SERVER['SCRIPT_NAME'], $_GET['modulo']);
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
        <script src="<?php echo _ruta_librerias_js . _js_dataTable; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2_es; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_datepicker; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_datepicker_es; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_librerias; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_validarcampos; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_librerias; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_script_js . 'asignar.js' ?>" type="text/javascript"></script>
        <style type="text/css">
            ul#nacionalidad{
                min-width:50px !important;
                width: 50px !important;

            }
        </style>
    </head>
    <body>
        <div class="panel panel-default" style="width : 90%;margin: auto;height: auto;position: relative; top:25px;">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Asignación de Citas</div>
            <div class="panel-body">
                <form name="frmasignar" id="frmasignar" method="post" enctype="multipart/form-data">
                    <table width="783" height="395" align="center">
                        <tr>
                            <td width="27" height="40" align="left">&nbsp;</td>
                            <td width="73" align="left">Cedula:</td>
                            <td width="235">
                                <div id="div_cedula" class="input-group">
                                    <div class="input-group-btn">
                                        <button style="font-size: 11px;" id="btn_nac" type="button" class="btn btn-default dropdown-toggle input-sm" data-toggle="dropdown">
                                            N
                                            <span class="caret"></span>
                                        </button>
                                        <ul id="nacionalidad" class="dropdown-menu">
                                            <li>
                                                <span id="N">N</span>
                                            </li>
                                            <li>
                                                <span id="V">V</span>
                                            </li>
                                            <li>
                                                <span id="E">E</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <input type="hidden" id="hnac" name="hnac" />
                                    <input type="text" class="form-control input-sm" id="cedula_p" name="cedula_p"  value="" maxlength="12" />
                                    <span class="input-group-btn ">
<!--                                        <button type="button" class="btn btn-primary btn-sm">
                                            <span class="glyphicon glyphicon-search"></span> 
                                        </button>-->
                                        <input style="font-size: 11px;" id="btnbuscar" type="button" class="btn btn-primary input-sm" value="Buscar"/>
<!--                                    <button type="button" style="font-size: 11px;" id="btnbuscar" class="btn btn-primary btn-sm">Buscar</button>-->
                                    </span>
                                </div>
                            </td>
                            <td width="70">
                                <img style="cursor: pointer" id="imgcedula" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                            </td>
                            <td width="75" height="40" align="left">Nombre:</td>
                            <td width="223">
                                <div style="margin-top: 10px" class="form-group">
                                    <input type="text" class="form-control input-sm" id="nombre" name="nombre"  disabled="disabled" maxlength="20" />
                                </div>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="27" height="40" align="left">&nbsp;</td>
                            <td width="73" height="40" align="left">Apellido:</td>
                            <td width="235">
                                <div style="margin-top: 10px" class="form-group">
                                    <input type="text" class="form-control input-sm" id="apellido" name="apellido" disabled="disabled" maxlength="50" />
                                </div>
                            </td>
                            <td width="70">&nbsp;</td>
                            <td width="75" height="40" align="left">Telefono:</td>
                            <td width="223">
                                <div style="margin-top: 10px" class="form-group">
                                    <input type="text" class="form-control input-sm" id="telefono" name="telefono" disabled="disabled"  maxlength="50" />
                                </div>
                            </td>
                            <td width="48">&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="40" align="left">&nbsp;</td>
                            <td height="40" align="left">Consultorio:</td>
                            <td>
                                <div id="div_num_consultorio" style="margin-top: 10px;" class="form-group">
                                    <select  name="num_consultorio" id="num_consultorio" class="form-control select2">
                                        <option value="0">Seleccione</option>
                                        <?php
                                        $result_cons = $obj_cons->getConsultorioAll();
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
                                <img src="../../imagenes/img_info.png" alt="img_info" name="imgconsultorio" width="15" height="15" id="imgconsultorio" style="cursor: pointer"/>
                            </td>
                            <td height="40" align="left">Fecha de Cita:</td>
                            <td>
                                <div id="div_fecha" style="margin-top: 10px" class="form-group">
                                    <input readonly style="background-color: #FFFFFF" class="form-control input-sm"  type="text" id="fecha" name="fecha" value="" />
                                </div>
                            </td>
                            <td>
                                <img style="cursor: pointer" id="imgfechacita" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                            </td>
                        </tr>
                        <tr>
                            <td height="40" align="left">&nbsp;</td>
                            <td>Turno:</td>
                            <td>
                                <div class="btn-group" data-toggle="buttons" >
                                    <label id="l_manana" class="btn btn-default btn-sm disabled">
                                        <input  type="radio" name="turno"  id="manana" value="1">
                                        MA&Ntilde;ANA
                                    </label>
                                    <label id="l_tarde" class="btn btn-default btn-sm disabled">
                                        <input type="radio" name="turno" id="tarde" value="2">
                                        TARDE
                                    </label>
                                </div>
                                <img style="cursor: pointer" id="imgturno" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                            </td>
                            <td>&nbsp;</td>
                            <td height="40" align="left">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="51"  colspan="7" align="center">
                                <div id="botones">
                                    <input class="btn btn-default btn-sm" id="btnaccion" name="btnaccion" type="button" value="Asignar" disabled="disabled" />
                                    <input class="btn btn-default btn-sm" id="btnlimpiar" name="btnlimpiar" type="button" value="Limpiar" />
                                    <input class="btn btn-default btn-sm" id="btnimprimir" name="btnimprimir" type="button" value="Ver para Imprimir" disabled="disabled" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td  colspan="7" align="center">
                                <div style="margin:auto;width:95%">
                                    <table  id="tabla_asignar" border="0" cellspacing="1" class="dataTable">
                                        <thead>
                                            <tr>
                                                <th>Fecha Cita</th>
                                                <th>Consultorio</th>
                                                <th>Turno</th>
                                                <th>Observación</th>
                                                <th>Modificar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td  colspan="7" align="center">&nbsp;</td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </body>
</html>