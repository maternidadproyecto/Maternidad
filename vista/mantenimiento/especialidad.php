<?php
session_start();
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));

require_once '../../librerias/globales.php';
require_once '../../modelo/mantenimientos/Especialidad.php';

$obj_mant = new Especialidad();
if (isset($_GET['modulo'])) {
    $_SESSION['cod_modulo'] = $_GET['modulo'];
    $obj_mant->url($_SERVER['SCRIPT_NAME'], $_GET['modulo']);
}

$img_mod = _img_dt . _img_dt_mod;
$img_del = _img_dt . _img_dt_del;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap_theme; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_estilos; ?>"/>

        <script src="<?php echo _ruta_librerias_js . _js_jquery; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_tooltip; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_dataTable; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_validarcampos; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_librerias; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_script_js . 'especialidad.js' ?>" type="text/javascript"></script>

    </head>
    <body>
        <div class="panel panel-default" style="width : 90%;margin: auto;height: auto;position: relative; top:25px;">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Registro de Especialidad</div>
            <div class="panel-body">
                <table width="679" border="0" align="center">
                    <tr>
                        <td align="center">
                            <form name="frmespecialidad" id="frmespecialidad" method="post" enctype="multipart/form-data">
                                <table width="610" align="center">
                                    <tr>
                                        <td width="184" height="40" align="right">Especialidad:</td>
                                        <td width="225">
                                            <div id="div_especialidad" style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="especialidad" name="especialidad" maxlength="50" />
                                            </div>
                                        </td>
                                        <td width="185">
                                            <img style="cursor: pointer" id="imgespecialidad" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  colspan="3" align="right">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td  colspan="3" align="center">
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
                        <td align="center">
                            <table id="tabla" border="0" cellspacing="1"  class="dataTable">
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Especialidad</th>
                                        <th>Modificar</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result  = $obj_mant->getEspecialidadAll();
                                    $es_array  = is_array($result) ? TRUE : FALSE;
                                    if($es_array === TRUE){
                                        for ($i = 0; $i < count($result); $i++) {
                                            ?>
                                            <tr>
                                                <td><?php echo $result[$i]['cod_especialidad']; ?></td>
                                                <td><?php echo $result[$i]['especialidad']; ?></td>
                                                <td><img class="modificar"  title="Modificar" style="cursor: pointer" src="<?php echo $img_mod ; ?>" width="18" height="18" alt="Modificar"/></td>
                                                <td><img class="eliminar"  title="Eliminar" style="cursor: pointer" src="<?php echo $img_del ?>" width="18" height="18"  alt="Eliminar"/></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>