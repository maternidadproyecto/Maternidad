<?php
session_start();
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));
require_once '../../librerias/globales.php';
require_once '../../modelo/seguridad/SubModulo.php';

$obj_submodulo = new SubModulo();
if (isset($_GET['modulo'])) {
    $_SESSION['cod_modulo'] = $_GET['modulo'];
    $obj_submodulo->url($_SERVER['SCRIPT_NAME'], $_GET['modulo']);
}
$data_mod['menu']   = TRUE;
$data_mod['campos'] = 'cod_modulo,modulo,posicion,activo';
$resul_mod          = $obj_submodulo->getModulo($data_mod);
$img_mod            = _img_dt . _img_dt_mod;
$img_del            = _img_dt . _img_dt_del;
?>

<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap_theme; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_select2; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_select2_bootstrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_estilos; ?>"/>
        
        <script src="<?php echo _ruta_librerias_js . _js_jquery; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_tooltip; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2_es; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_dataTable; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_validarcampos; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_librerias; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_script_js . 'modulo.js' ?>" type="text/javascript"></script>
        <style type="text/css">
            tr td.registro{
                cursor: pointer;
            }
            div[class="tooltip-inner"] {
                max-width: 350px;
                font-family: Verdana,Arial,Helvetica,sans-serif;
                font-size: 10px;
            }
        </style>
    </head>
    <body>
        <!-- Inicio Modulo -->
        <div class="panel panel-default" id="divmodulo" style="width : 90%;margin: auto;height: auto;position: relative; top:25px;">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Registro de Modulo</div>
            <div class="panel-body">
                <table width="679" border="0" align="center">
                    <tr>
                        <td align="center">
                            <form name="frmmodulo" id="frmmodulo" method="post" enctype="multipart/form-data">
                                <table width="546" align="center">
                                    <tr>
                                        <td width="73" height="40">Modulo:</td>
                                        <td width="405">
                                            <div id="div_modulo" style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="modulo" name="modulo" value="" maxlength="20" />
                                            </div>

                                        </td>
                                        <td width="52">
                                            <img  style="cursor: pointer" id="img_modulo" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="40">Posici&oacute;n Men&uacute;:</td>
                                        <td>
                                            <div id="div_modposicion" class="form-group" style="margin-top: 10px">
                                                <input type="text" class="form-control input-sm" id="mod_posicion" name="mod_posicion" value="" maxlength="20" />
                                            </div>
                                        </td>
                                        <td>
                                            <img style="cursor: pointer" id="img_posicion" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="40" align="left">Estatus:</td>
                                        <td>
                                            <div class="btn-group" data-toggle="buttons" >
                                                <label id="l_mod_activo" class="btn btn-success active btn-sm">
                                                    <input  type="radio" name="mod_estatus" checked="checked" id="mod_activo" value="1">
                                                    Activo
                                                </label>
                                                <label id="l_mod_inactivo" class="btn btn-default btn-sm">
                                                    <input type="radio" name="mod_estatus" id="mod_inactivo" value="0">
                                                    Inactivo
                                                </label>
                                            </div>
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td height="40" align="left">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td  colspan="3" align="center">
                                            <div id="div_mensaje" class="" style="padding: 5px;display: none;width: 300px;height:30px;"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  colspan="3" align="center">
                                            <div id="botones">
                                                <input class="btn btn-primary btn-sm" id="btnaccion" name="btnaccion" type="button" value="Guardar" />
                                                <input style="display: none" class="btn btn-danger btn-sm" id="btnlistar" name="btnlistar" type="button" value="Sub Modulos"/>
                                                <input class="btn btn-default btn-sm" id="btnlimpiar" name="btnlimpiar" type="button" value="Limpiar" />
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <!-- DataTable-->
                        <td align="center">
                            <table style="width:100%;" border="0" align="center" cellspacing="1" class="dataTable" id="tabla_modulo" >
                                <thead>
                                    <tr>
                                        <th width="58">Codigo</th>
                                        <th width="64">Modulo</th>
                                        <th width="64">Posici&oacute;n Men&uacute;</th>
                                        <th width="64">Estatus</th>
                                        <th width="81">Acción</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    
                                    <?php
                                    for ($i = 0; $i < count($resul_mod); $i++) {
                                        $estatus = 'Inactivo';
                                        if ($resul_mod[$i]['activo'] == 1) {
                                            $estatus = 'Activo';
                                        }
                                        ?>
                                    <tr>
                                            <td class="registro"><?php echo $resul_mod[$i]['cod_modulo']; ?></td>
                                            <td><?php echo $resul_mod[$i]['modulo']; ?></td>
                                            <td><?php echo $resul_mod[$i]['posicion']; ?></td>
                                            <td><?php echo $estatus; ?></td>
                                            <td>
                                                <img class="modificar" style="cursor: pointer" src="<?php echo $img_mod ?>" width="18" height="18" alt="Modificar"/>                                  
                                                &nbsp;
                                                <?php
                                                if ($resul_mod[$i]['cod_modulo'] > 2) {
                                                    ?>
                                                    <img class="eliminar"  title="Eliminar" style="cursor: pointer" src="<?php echo $img_del ?>" width="18" height="18"  alt="Eliminar"/>
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
                    </tr><!-- DataTable-->
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- Fin Modulo-->
        
        <!-- Inicio Sub Modulos -->

        <div class="panel panel-default" id="divsubmodulo" style="width : 90%;margin: auto;height: auto;position: relative; top:25px; display: none">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Registro de SubModulo</div>
            <div class="panel-body">
                <table width="679" border="0" align="center">
                    <tr>
                        <td align="center">
                            <form name="frmsubmodulo" id="frmsubmodulo" method="post" enctype="multipart/form-data">
                                <table width="675" align="center">
                                    <tr>
                                        <td width="54" height="40">Modulo:</td>
                                        <td width="229">
                                            <select  style="" name="nommodulo" id="nommodulo" class="form-control select2">
                                                <option value="0">Seleccione</option>
                                                <?php
                                                for ($i = 0; $i < count($resul_mod); $i++) {
                                                    ?>
                                                    <option style="font-size: 10px;" value="<?php echo $resul_mod[$i]['cod_modulo']; ?>"><?php echo $resul_mod[$i]['modulo']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td width="59">
                                            <img style="cursor: pointer" id="img_nommodulo" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td width="75" height="40">SubModulo:</td>
                                        <td width="215">
                                            <div id="div_submodulo" style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="submodulo" name="submodulo"   value="" maxlength="50" />
                                            </div>
                                        </td>
                                        <td width="15">
                                            <img style="cursor: pointer" id="img_submodulo" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="40" align="left">Estatus:</td>
                                        <td>
                                            <div class="btn-group" data-toggle="buttons" >
                                                <label id="l_sbmod_activo" class="btn btn-success active btn-sm">
                                                    <input  type="radio" name="sbmod_estatus" checked="checked" id="sbmod_activo" value="1">
                                                    Activo 
                                                </label>
                                                <label id="l_sbmod_inactivo" class="btn btn-default btn-sm">
                                                    <input type="radio" name="sbmod_estatus" id="sbmod_inactivo" value="0">
                                                   Inactivo
                                                </label>
                                            </div>
                                        </td>
                                      <td>&nbsp;</td>
                                        <td height="40">Posici&oacute;n Men&uacute;:</td>
                                        <td>
                                            <div id="div_posicion" class="form-group" style="margin-top: 10px">
                                                <input type="text" class="form-control input-sm" id="sbm_posicion" name="sbm_posicion" value="" maxlength="20" />
                                            </div>
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td height="40">Ruta:</td>
                                        <td>
                                            <div id="div_ruta" style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="ruta" name="ruta" maxlength="50"  value="" />
                                            </div></td>
                                        <td>
                                            <img style="cursor: pointer" id="img_ruta" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td  colspan="6" align="right">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td  colspan="6" align="center">
                                            <div id="botones">
                                                <input class="btn btn-primary btn-sm" id="btnaccionsub"   name="btnaccionsub"   type="button" value="Guardar" />
                                                <input class="btn btn-default btn-sm" id="btnlimpiarsub"  name="btnlimpiarsub"  type="button" value="Limpiar" />
                                                <input class="btn btn-danger btn-sm"  id="btnrestablecer"  name="btnrestablecer" type="button" value="Restablecer" />                          
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <table style="width:100%;" border="0" align="center" cellspacing="1" class="dataTable" id="tabla_submodulo" >
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Modulo</th>
                                        <th>SubModulo</th>
                                        <th>Posici&oacute;n</th>
                                        <th>Estatus</th>
                                        <th>Acci&oacute;n</th>
                                    </tr>
                                </thead>
                                <tbody>

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
        <!-- Fin SubModulo -->
    </body>
</html>