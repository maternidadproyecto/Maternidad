<?php

session_start();
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));

require_once '../../librerias/globales.php';
require_once '../../modelo/paciente/Paciente.php';
$objmod = new Paciente();

if (isset($_GET['modulo'])) {
    $objmod->url($_SERVER['SCRIPT_FILENAME'], $_GET['modulo']);
}

$result_mun = $objmod->getMunicipio();
$result_tel = $objmod->getCodLocal();
$result_cel = $objmod->getCodCelular();

$img_mod = _img_dt . _img_dt_mod;
$img_del = _img_dt . _img_dt_del;
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
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_datepicker; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_datepicker_es; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_dataTable; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2_es; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_tooltip; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_validarcampos; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_librerias; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_script_js . 'paciente.js' ?>" type="text/javascript"></script>
        <style type="text/css">
            ul#cod_local,ul#cod_cel,ul#nacionalidad{
                min-width:50px !important;
                width: 50px !important;

            }
            ul#cod_local > li > span,ul#cod_cel > li > span{
                text-align:center !important;
                padding: 2px !important;
            }
        </style>
    </head>
    <body>
        <div class="panel panel-default" style="width : 90%;margin: auto;height: auto;position: relative; top:25px;">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Agregar Paciente</div>
            <div class="panel-body">
                <table width="681" border="0" align="center">
                    <tr>
                        <td width="675" align="center">
                            <form name="frmpaciente" id="frmpaciente" method="post" enctype="multipart/form-data">
                                <table width="676" align="center">
                                    <tr>
                                        <td width="63" height="34" align="left">Cedula:</td>
                                        <td width="226">
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
                                                <input type="text" class="form-control input-sm" id="cedula_p" name="cedula_p"  value="" maxlength="13"/>
                                            </div>
                                        </td>
                                        <td width="77">
                                            <img style="cursor: pointer" id="imgcedula" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td width="56" height="34" align="left">Nombre:</td>
                                        <td width="216">
                                            <div id="div_nombre" style="margin-top: 10px" class="form-group">
                                                <input type="text"  class="form-control input-sm" id="nombre" name="nombre" value="" maxlength="22" />
                                            </div>
                                        </td>
                                        <td width="15">
                                            <img style="cursor: pointer" id="imgnombre" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="63" height="34" align="left">Apellido:</td>
                                        <td width="226">
                                            <div id="div_apellido" style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="apellido" name="apellido" value="" maxlength="22" />
                                            </div>
                                        </td>
                                        <td width="77">
                                            <img style="cursor: pointer" id="imgapellido" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td width="56" height="34" align="left">Telefono:</td>
                                        <td width="216">
                                            <div id="div_telefono" class="input-group">
                                                <div class="input-group-btn">
                                                    <button style="font-size: 11px;" id="btn_codlocal" type="button" class="btn btn-default dropdown-toggle input-sm" data-toggle="dropdown">
                                                        Cod
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul id="cod_local" class="dropdown-menu">
                                                        <li>
                                                            <span id="0">Cod</span>
                                                        </li>
                                                        <?php
                                                        for ($i = 0; $i < count($result_tel); $i++) {
                                                            ?>
                                                            <li>
                                                                <span id="<?php echo $result_tel[$i]['cod_telefono']; ?>">0<?php echo $result_tel[$i]['codigo']; ?></span>
                                                            </li>
                                                            <?php
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                                <input type="hidden" id="hcod_telefono" name="hcod_telefono" />
                                                <input type="text" class="form-control input-sm" id="telefono" name="telefono" value="" maxlength="12" />
                                            </div>
                                        </td>
                                        <td width="15">
                                            <img style="cursor: pointer" id="imgtelefono" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="63" height="48" align="left">Fech. Naci.:</td>
                                        <td width="226">
                                            <div id="div_fecha" style="margin-top: 10px;" class="form-group">
                                                <input readonly="false" style="background-color: #FFFFFF" type="text" class="form-control input-sm" id="fecha_nacimiento" name="fecha_nacimiento" value="" maxlength="7" />
                                            </div>
                                        </td>
                                        <td width="77">
                                            <img style="cursor: pointer" id="imgfechanaci" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td width="56" height="48" align="left">Celular:</td>
                                        <td width="216">
                                            <div id="div_celular" class="input-group">
                                                <div class="input-group-btn">
                                                    <button style="font-size: 11px;" id="btn_codcel" type="button" class="btn btn-default dropdown-toggle input-sm" data-toggle="dropdown">
                                                        Cod
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul id="cod_cel" class="dropdown-menu">
                                                        <li>
                                                            <span id="0">Cod</span>
                                                        </li>
                                                        <?php
                                                        for ($i = 0; $i < count($result_cel); $i++) {
                                                            ?>
                                                            <li>
                                                                <span id="<?php echo $result_cel[$i]['cod_telefono']; ?>">0<?php echo $result_cel[$i]['codigo']; ?></span>
                                                            </li>
                                                            <?php
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                                <input type="hidden" id="hcod_celular" name="hcod_celular" />
                                                <input  type="text" class="form-control input-sm" id="celular" name="celular" value="" maxlength="12" />
                                            </div>
                                        </td>
                                        <td width="15">
                                            <img style="cursor: pointer" id="imgcelular" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="63" height="58" align="left">Municipio:</td>
                                        <td>
                                            <div id="div_municipio" style="margin-top: 10px;" class="form-group">
                                                <select name="municipio" id="municipio" class="form-control select2 input-sm">
                                                    <option value="0">Seleccione</option>
                                                    <?php
                                                    for ($i = 0; $i < count($result_mun); $i++) {
                                                        ?>
                                                        <option style="font-size: 10px;" value="<?php echo $result_mun[$i]['codigo_municipio']; ?>">
                                                            <?php echo $result_mun[$i]['municipio'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td width="77">
                                            <img style="cursor: pointer" id="imgmunicipio" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td width="56" height="58" align="left">Sector:</td>
                                        <td>
                                            <div id="div_sector" style="margin-top: 10px;" class="form-group">
                                                <select name="sector" id="sector" class="form-control select2 input-sm">
                                                    <option value="0">Seleccione</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td width="15">
                                            <img style="cursor: pointer" id="imgsector" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="63" height="49" align="left">Direcci&oacute;n:</td>
                                        <td colspan="4">
                                            <div id="div_direccion" class="form-group">
                                                <textarea class="form-control input-sm" id="direccion" name="direccion" rows="2" cols="95"></textarea>
                                            </div>
                                        </td>
                                        <td width="15">
                                            <img style="cursor: pointer" id="imgdireccion" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  colspan="9" align="right">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td  colspan="6" align="center">
                                            <div id="botones">
                                                <input class="btn btn-default btn-sm" id="btnaccion" name="btnaccion" type="button" value="Agregar" />
                                                <input class="btn btn-default btn-sm" id="btnlimpiar" name="btnlimpiar" type="button" value="Limpiar" />
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <table  border="0" cellspacing="1" id="tabla_paciente" class="dataTable" style="margin: auto;width:100%">
                                <thead>
                                    <tr>
                                        <th>Cedula</th>
                                        <th>Nombres</th>
                                        <th>Fech. Naci.</th>
                                        <th>Telefono</th>
                                        <th>Modificar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $objmod->getPacienteAll();
                                    $es_array = is_array($result) ? TRUE : FALSE;
                                    if ($es_array === TRUE) {
                                        for ($i = 0; $i < count($result); $i++) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $result[$i]['cedula_p']; ?></td>
                                                <td>
                                                    <?php echo $result[$i]['nombres']; ?></td>
                                                <td>
                                                    <?php echo $result[$i]['fecha']; ?></td>
                                                <td>
                                                    <?php echo $result[$i]['telefono']; ?></td>
                                                <td>
                                                    <img class="modificar"  title="Modificar" style="cursor: pointer" src="<?php echo $img_mod ?>" width="18" height="18" alt="Modificar"/>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>