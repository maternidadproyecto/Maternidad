<?php
session_start();
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));

require_once '../../librerias/globales.php';
require_once '../../modelo/mantenimientos/Consultorio.php';
$obj_cons = new Consultorio();
if (isset($_GET['modulo'])) {
    $_SESSION['cod_modulo'] = $_GET['modulo'];
    $obj_cons->url($_SERVER['SCRIPT_NAME'], $_GET['modulo']);
}

$result_especiaidad = $obj_cons->getEspecialidadAll();

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
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_tooltip; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2_es; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_validarcampos; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_librerias; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_script_js . 'consultorio.js' ?>" type="text/javascript"></script>
    </head>
    <body>
        <div class="panel panel-default" style="width : 90%;margin: auto;height: auto;position: relative; top:25px;">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Registro de Consultorio</div>
            <div class="panel-body">
                <table width="787" border="0" align="center">
                    <tr>
                        <td align="center">
                            <form name="frmconsultorio" id="frmconsultorio" method="post" enctype="multipart/form-data">
                                <table width="734" border="0">
                                    <tr>
                                        <td width="73" height="40" align="left">Consultorio:</td>
                                        <td width="253">
                                            <div id="div_consultorio" style="margin-top: 10px;width:252px;" class="form-group">
                                                <input type="text" class="form-control input-sm" id="consultorio" name="consultorio" />
                                            </div>
                                        </td>
                                        <td width="45">
                                            <img style="cursor: pointer" id="imgconsultorio" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td width="79">Especialidad:</td>
                                        <td width="253">
                                            <div id="div_especialidad" style="margin-top: 10px;" class="form-group">
                                                <select id="especialidad" name="especialidad" class="form-control input-sm select2">
                                                    <option value="0">Seleccione</option>
                                                    <?php
                                                    for ($i = 0; $i < count($result_especiaidad); $i++) {
                                                        ?>
                                                        <option value="<?php echo $result_especiaidad[$i]['cod_especialidad'] ?>"><?php echo $result_especiaidad[$i]['especialidad'] ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td width="8">
                                            <img style="cursor: pointer" id="imgespecialidad" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Turno:</td>
                                        <td>
                                            <div class="btn-group" data-toggle="buttons" >
                                                <label id="l_manana" class="btn btn-primary  btn-sm">
                                                    <input type="checkbox" name="turno[manana]" id="manana"  value="1" />
                                                    <span class="activar" id="sp_manana">MAÑANA</span> 
                                                </label>
                                            </div>
                                            <div class="btn-group" data-toggle="buttons" >
                                                <label id="l_tarde" class="btn btn-primary  btn-sm">
                                                    <input type="checkbox"  name="turno[tarde]" id="tarde"  value="1" />
                                                    <span class="activar" id="sp_tarde">TARDE</span> 
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <img style="cursor: pointer" id="imgturno" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" align="center">
                                            <div id="botones">
                                                <input class="btn btn-default btn-sm" id="btnaccion" name="btnaccion"   type="button" value="Agregar" />
                                                <input class="btn btn-default btn-sm" id="btnlimpiar" name="btnlimpiar" type="button" value="Limpiar" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" align="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" align="center">
                                            <table style="width:100%" border="0" class="dataTable" id="tabla_consultorio">
                                                <thead>
                                                    <tr>
                                                        <th width="75">Consultorio</th>
                                                        <th width="159">Especialidad</th>
                                                        <th width="77">Turno </th>
                                                        <th width="137">Modificar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $result_consu = $obj_cons->getConsultorioAll();
                                                    $es_array     = is_array($result_consu) ? TRUE : FALSE;
                                                    if($es_array === TRUE){
                                                        for ($i = 0; $i < count($result_consu); $i++) {
                                                            $turno = array();
                                                            if($result_consu[$i]['manana'] == 1){
                                                                $turno['manana']= 'MAÑANA';
                                                            }
                                                            if($result_consu[$i]['tarde'] == 1){
                                                                $turno['tarde']= 'TARDE';
                                                            }
                                                            $turnos = implode(',',$turno)
                                                            
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $result_consu[$i]['consultorio']; ?></td>
                                                                <td><?php echo $result_consu[$i]['especialidad']; ?></td>
                                                                <td><?php echo $turnos; ?></td>
                                                                <td>
                                                                    <img  class="modificar"  title="Modificar" style="cursor: pointer" src="<?php echo $img_mod; ?>" width="18" height="18" alt="Modificar"/>
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
                                </table>
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>