<?php
session_start();
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));

require '../../librerias/globales.php';
require_once '../../modelo/seguridad/SubModulo.php';

$obj_submodulo = new SubModulo();
if (isset($_GET['modulo'])) {
    $_SESSION['cod_modulo'] = $_GET['modulo'];
    $obj_submodulo->url($_SERVER['SCRIPT_NAME'], $_GET['modulo']);
}
require_once '../../modelo/seguridad/Usuario.php';
$obj           = new Usuario();

$datos_perfil['tabla']  = 's_perfil';
$datos_perfil['campos'] = 'codigo_perfil,perfil';
$result_perfil = $obj->getPerfil($datos_perfil);

$datos['sql'] = "SELECT 
                    u.id_usuario,
                    u.usuario, 
                    IF(u.activo=1,'Activo','Inactivo') AS activo,
                    p.perfil,
                    DATE_FORMAT(u.fecha_creacion, '%d-%m-%Y') AS fecha
                FROM s_usuario AS u 
                INNER JOIN s_perfil  AS p ON u.codigo_perfil=p.codigo_perfil";
$result = $obj->getUsuario($datos);
$img_mod                = _img_dt . _img_dt_mod;
$img_del                = _img_dt . _img_dt_del;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_estilos; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_select2; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_select2_bootstrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap_switch; ?>"/>

        <script src="<?php echo _ruta_librerias_js . _js_jquery; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_dataTable; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2_es; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_tooltip; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_switch; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_validarcampos; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_script_js . 'usuario.js' ?>" type="text/javascript"></script>
    </head>
    <body>

        <div class="panel panel-default" style="width : 90%;margin: auto;height: auto;position: relative; top:25px;">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Datos del Usuario</div>
            <div class="panel-body">
                <table width="681" border="0" align="center">
                    <tr>
                        <td width="675" align="center">
                            <form name="frmusuario" id="frmusuario" method="post" enctype="multipart/form-data">
                                <table width="676" align="center">
                                    <tr>
                                        <td width="80" height="40" align="left">Usuario:</td>
                                        <td width="235">
                                            <div id="div_usuario" style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="usuario" name="usuario" value="" maxlength="20"/>
                                            </div>
                                        </td>
                                        <td width="59"><span class="obligatorio">*</span></td>

                                        <td width="38" height="40" align="left">Clave:</td>
                                        <td width="221">
                                            <div id="div_clave" style="margin-top: 10px" class="form-group">
                                                <input type="password" class="form-control input-sm" id="clave" name="clave"  value="" maxlength="20"/>
                                            </div>
                                        </td>
                                        <td width="15"><span class="obligatorio">*</span></td>
                                    </tr>
                                    <tr>
                                        <td width="80" height="40" align="left">Repetir Clave:</td>
                                        <td width="235">
                                            <div id="div_repclave" style="margin-top: 10px" class="form-group">
                                                <input type="password" class="form-control input-sm" id="repclave" name="repclave" value="" maxlength="20"/>
                                            </div>
                                        </td>
                                        <td width="59"><span class="obligatorio">*</span></td>

                                        <td width="38" height="40" align="left">Perfil:</td>
                                        <td width="221">
                                            <div id="div_perfil" style="margin-top: 10px;" class="form-group">
                                                <select id="perfil" name="perfil" class="form-control select2 input-sm">
                                                    <option value="0">Seleccione</option>
                                                    <?php
                                                    for ($i = 0; $i < count($result_perfil); $i++) {
                                                        ?>
                                                        <option  value="<?php echo $result_perfil[$i]['codigo_perfil'] ?>"><?php echo $result_perfil[$i]['perfil'] ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td width="15"><span class="obligatorio">*</span></td>
                                    </tr>
                                    <tr>
                                        <td width="80" height="40" align="left">Estatus:</td>
                                        <td width="235">
                                            <div class="btn-group" data-toggle="buttons" >
                                                <label id="l_u_activo" class="btn btn-success active btn-sm">
                                                    <input  type="radio" name="u_estatus" checked="checked" id="u_activo" value="1">
                                                    Activo
                                                </label>
                                                <label id="l_u_inactivo" class="btn btn-default btn-sm">
                                                    <input type="radio" name="u_estatus" id="u_inactivo" value="0">
                                                    Inactivo
                                                </label>
                                            </div>
                                        </td>
                                        <td width="59"><span class="obligatorio">*</span></td>
                                    </tr>

                                    <tr>
                                      <td  colspan="6" align="right"><span style="color: #ff0000;margin-left">Campo Obligatorio *</span></td>
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
                            <table id="tabla_usuarios" border="0" cellspacing="1"  class="dataTable" >
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Perfil</th>
                                        <th>Estatus</th>
                                        <th>Fecha</th>
                                        <th>Acci&oacute;n</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($i = 0; $i < count($result); $i++) {
                                        ?>
                                        <tr>
                                            <td><?php echo $result[$i]['id_usuario']; ?></td>
                                            <td><?php echo $result[$i]['usuario']; ?></td>
                                            <td><?php echo $result[$i]['perfil']; ?></td>
                                            <td><?php echo $result[$i]['activo']; ?></td>
                                            <td><?php echo $result[$i]['fecha']; ?></td>
                                            <td>
                                                <img class="modificar"  title="Modificar" style="cursor: pointer" src="<?php echo $img_mod; ?>" width="18" height="18" alt="Modificar"/>
                                                <?php 
                                                if($result[$i]['id_usuario'] > 1){
                                                ?>
                                                    <img class="eliminar"  title="Eliminar" style="cursor: pointer" src="<?php echo $img_del; ?>" width="18" height="18"  alt="Eliminar"/>
                                                <?php 
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
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