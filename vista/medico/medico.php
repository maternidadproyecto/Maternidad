<?php
error_reporting(0);
session_start();
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));

require '../../librerias/globales.php';

require_once '../../modelo/medico/Medico.php';

$objmod = new Medico();
if (isset($_GET['modulo'])) {
    $objmod->url($_SERVER['SCRIPT_FILENAME'], $_GET['modulo']);
}

$result_esp = $objmod->getEspecialidadAll();
$result_cod = $objmod->getCod();

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
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_estilos; ?>"/>

        <script src="<?php echo _ruta_librerias_js . _js_jquery; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_dataTable; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2_es; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_tooltip; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_validarcampos; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_librerias; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_script_js .'medico.js'?>" type="text/javascript"></script>
        <style type="text/css">
            ul#cod_local,ul#cod_celul#nacionalidad{
                min-width:50px !important;
                width: 50px !important;

            }
            ul#cod_local > li > span,ul#cod_cel > li > span{
               text-align:center !important;
               padding: 2px !important;
            }
 
            button.dropdown-toggle{
                /*margin-top:-1px !important;*/
            }
        </style>
    </head>
    <body>
        <div class="panel panel-default" style="width : 90%;margin: auto;height: auto;position: relative; top:25px;">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Datos del Medico</div>
            <div class="panel-body">
                <table width="799" border="0" align="center">
                    <tr>
                        <td width="755" align="center">
                            <form name="frmpersonalmedico" id="frmpersonalmedico" method="post" enctype="multipart/form-data">
                                <table width="712" align="center">
                                    <tr>
                                        <td width="79" height="40" align="left">C&eacute;dula:</td>
                                        <td width="220">
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
                                                <input  type="text" class="form-control input-sm" id="cedula_pm" name="cedula_pm" value="" maxlength="10" />
                                            </div>
                                        </td>
                                        <td width="72">
                                            <img style="cursor: pointer" id="imgcedula" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td width="73" height="40" align="left">Nombre:</td>
                                        <td width="223">
                                          <div id="div_nombre" style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="nombre" name="nombre"  value="" maxlength="20" />
                                            </div>
                                        </td>
                                        <td width="17">
                                            <img style="cursor: pointer" id="imgnombre" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="79" height="40" align="left">Apellido:</td>
                                        <td width="220">
                                          <div id="div_apellido" style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="apellido" name="apellido" value="" maxlength="20" />
                                            </div>
                                        </td>
                                        <td width="72">
                                            <img style="cursor: pointer" id="imgapellido" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td width="73" height="40" align="left">Tel&eacute;fono:</td>
                                        <td width="223">
                                         <div id="div_telefono" class="input-group">
                                                <div class="input-group-btn">
                                                    <button style="font-size: 11px;" id="btn_codlocal" type="button" class="btn btn-default dropdown-toggle input-sm" data-toggle="dropdown">
                                                        Cod
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul id="cod_local" class="dropdown-menu">
                                                        <li><span id="0">Cod</span></li>
                                                        <?php
                                                            for ($i = 0; $i < count($result_cod); $i++) {
                                                            ?>
                                                            <li><span id="<?php echo $result_cod[$i]['cod_telefono'];?>">0<?php echo $result_cod[$i]['codigo'];?></span></li>
                                                            <?php
                                                            }
                                                        ?>
                                                    </ul>
                                                </div>
                                                <input type="hidden" id="hcod_telefono" name="hcod_telefono" />
                                                <input type="text" class="form-control input-sm" id="telefono" name="telefono" value="" maxlength="12" />
                                            </div>
                                        </td>
                                        <td width="17">
                                            <img style="cursor: pointer" id="imgtelefono" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="79" height="40" align="left">Especialidad:</td>
                                        <td>
                                            <div id="div_cod_esp" style="margin-top: 10px;" class="form-group">
                                                <select name="cod_esp"  id="cod_esp" class="form-control select2 input-sm">
                                                    <option value="0">Seleccione</option>
                                                    <?php
                                                    for ($i = 0; $i < count($result_esp); $i++) {
                                                        ?>
                                                        <option value="<?php echo $result_esp[$i]['cod_especialidad'] ?>"><?php echo $result_esp[$i]['especialidad'] ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td width="72">
                                            <img style="cursor: pointer" id="imgespecialidad" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td width="73" height="40" align="left">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td width="17">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td height="40" align="left">Direcci&oacute;n:</td>
                                      <td colspan="4"><div id="div_direccion" class="form-group">
                                        <textarea class="form-control input-sm" id="direccion" name="direccion" rows="2" cols="62" maxlength="150"></textarea>
                                      </div></td>
                                      <td><img style="cursor: pointer" id="imgdireccion" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/></td>
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
                                    <tr>
                                      <td  colspan="6" align="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td  colspan="6" align="center">
                                      <table style="width:100%" border="0" cellspacing="1"  class="dataTable" id="tabla">
                                        <thead>
                                          <tr>
                                            <th>Cedula</th>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>T&eacute;lefono</th>
                                            <th>Modificar</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $result   = $objmod->getPersonalMedicoAll();
                                            $es_array = is_array($result) ? TRUE : FALSE;
                                            if ($es_array === TRUE) {
                                                for ($i = 0; $i < count($result); $i++) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $result[$i]['cedula_pm']; ?></td>
                                                        <td><?php echo $result[$i]['nombre']; ?></td>
                                                        <td><?php echo $result[$i]['apellido']; ?></td>
                                                        <td><?php echo $result[$i]['telefono']; ?></td>
                                                        <td><img class="modificar"  title="Modificar" style="cursor: pointer" src="<?php echo $img_mod ?>" width="18" height="18" alt="Modificar"/></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                      </table>
                                      </td>
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