<?php
session_start();
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));

require_once '../../librerias/globales.php';
require_once '../../modelo/seguridad/Perfil.php';
require_once '../../modelo/seguridad/SubModulo.php';
$seguridad = new Seguridad();
$obj_submodulo = new SubModulo();
if (isset($_GET['modulo'])) {
    $_SESSION['cod_modulo'] = $_GET['modulo'];
    $seguridad->url($_SERVER['SCRIPT_FILENAME'], $_GET['modulo']);
}


$objperf                = new Perfil();
$datos_perfil['campos'] = 'codigo_perfil,perfil';
$result_perfil          = $objperf->getPerfil($datos_perfil);
$img_mod                = _img_dt . _img_dt_mod;
$img_del                = _img_dt . _img_dt_del;
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap_theme; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_estilos; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_select2; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_select2_bootstrap; ?>"/>


        <script src="<?php echo _ruta_librerias_js . _js_jquery; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_tooltip; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_dataTable; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2_es; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_validarcampos; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_librerias; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_script_js . 'perfil.js' ?>" type="text/javascript"></script>
    </head>
    <body>
        <div class="panel panel-default" id="divperfil" style="width : 90%;margin: auto;height: auto;position: relative; top:25px;">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Registro de Perfil</div>
            <div class="panel-body">
                <table width="679" border="0" align="center">
                    <tr>
                        <td align="center">
                            <form name="frmperfil" id="frmperfil" method="post" enctype="multipart/form-data">
                                <table width="610" align="center">
                                    <tr>
                                        <th width="184" height="40" style="text-align:right;padding-right: 5px;">Perfil:</th>
                                        <td width="225">
                                            <div id="div_perfil" style="margin-top: 10px" class="form-group">
                                                <input class="form-control input-sm" style="text-transform: capitalize" type="text" id="perfil" name="perfil" value="" maxlength="20" />
                                            </div>
                                        </td>
                                        <td width="185">
                                            <img  style="cursor: pointer" id="img_perfil" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  colspan="3" align="right">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td  colspan="3" align="center">
                                            <div id="botones">
                                                <input class="btn btn-primary btn-sm" id="btnaccion"  name="btnaccion"  type="button" value="Guardar" />
                                                <input class="btn btn-default btn-sm" id="btnlimpiar" name="btnlimpiar" type="button" value="Limpiar" />
                                                <input class="btn btn-primary btn-sm" id="btnlistar" name="btnlistar" type="button" disabled="disabled" value="Privilegios" />
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <table  align="center" cellspacing="1" class="dataTable"  id="tabla_perfil" >
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Perfil</th>
                                        <th>Acci&oacute;n</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($i = 0; $i < count($result_perfil); $i++) {
                                        ?>
                                        <tr>
                                            <td style="cursor: pointer" title="Click para ver Privilegios"><?php echo $result_perfil[$i]['codigo_perfil']; ?></td>
                                            <td style="cursor: pointer" title="Click para ver Privilegios"><?php echo $result_perfil[$i]['perfil']; ?></td>
                                            <td>
                                                <img class="modificar"  title="Modificar" style="cursor: pointer" src="<?php echo $img_mod ?>" width="18" height="18" alt="Modificar"/>                                  
                                                <?php 
                                                if($result_perfil[$i]['codigo_perfil'] > 1){
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
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- Privilegios -->
        <div class="panel panel-default" id="privilegios" style="width : 90%;margin: auto;height: auto; position: relative; top:25px;display: none">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Perfiles de Usuarios</div>
            <div class="panel-body">
                <table width="679" border="0" align="center">
                    <tr>
                        <td align="center">
                            <form name="frmprivilegio" id="frmprivilegio" method="post" enctype="multipart/form-data">
                                <table width="800" align="center">
                                    <tr>
                                        <th width="236" height="48">&nbsp;</th>
                                        <th width="30" align="right">Perfil:</th>
                                      <td width="298"> 
                                        <div style=";width: 290px;">
                                                <span style="font-size: 15px;width: 500px;" class="label label-info" id="nom_perfil"></span>
                                        </div>
                                      </td>
                                        <td width="170">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td  colspan="4" align="center">
                                            <table style="width:100%;" border="0" align="center" cellspacing="1" class="dataTable" id="tabla_privilegios">
                                                <thead>
                                                    <tr style="background-color: #BF2AB2;color: #FFFFFF;font-size: 11px;">
                                                        <th>Modulo</th>
                                                        <th style="width: 30% !important">Sub Modulo</th>
                                                        <th>Activar</th>
                                                        <th>Agregar</th>
                                                        <th>Modificar</th>
                                                        <th>Eliminar</th>
                                                        <th>Consultar</th>
                                                        <th>Imprimir</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $data_mod['sql'] = 'SELECT 
                                                                            sm.cod_submodulo,
                                                                            m.modulo,
                                                                            sm.sub_modulo,
                                                                            IF(pp.cod_submodulo=sm.cod_submodulo,1,0) AS activar,
                                                                            IF(pp.agregar=1,1,0) AS agregar,IF(pp.modificar=1,1,0) AS modificar,
                                                                            IF(pp.eliminar=1,1,0) AS eliminar
                                                                        FROM s_modulo AS m
                                                                        INNER JOIN s_sub_modulo AS sm ON m.cod_modulo=sm.cod_modulo
                                                                        LEFT JOIN s_perfil_privilegio pp ON sm.cod_submodulo=pp.cod_submodulo
                                                                        GROUP BY sm.cod_submodulo
                                                                        ORDER BY sm.cod_submodulo';
                                                    $resul_mod = $obj_submodulo->getSubModulo($data_mod);
                                                    
                                                    for ($i = 0; $i < count($resul_mod); $i++) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $resul_mod[$i]['modulo'] ?></td>
                                                            <td><?php echo $resul_mod[$i]['sub_modulo'] ?>
                                                            </td>
                                                            <td align="center">
                                                                <div class="btn-group" data-toggle="buttons" >
                                                                    <label id="lbl_ac_<?php echo $resul_mod[$i]['cod_submodulo'] ?>" class="btn btn-primary  btn-sm activar">
                                                                        <input type="checkbox" class="activar" name="activar[]" id="ac_<?php echo $resul_mod[$i]['cod_submodulo'] ?>"  value="<?php echo $resul_mod[$i]['cod_submodulo'] ?>" />
                                                                        <span class="activar" id="sp_ac_<?php echo $resul_mod[$i]['cod_submodulo'] ?>">NO</span> 
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td align="center">
                                                                <div class="btn-group" data-toggle="buttons" >
                                                                    <label id="lbl_add_<?php echo $resul_mod[$i]['cod_submodulo'] ?>" class="btn btn-primary btn-sm disabled agregar" >
                                                                        <input type="checkbox" class="agregar" name="agregar[]" id="add_<?php echo $resul_mod[$i]['cod_submodulo'] ?>" value="1"/>
                                                                        <span  class="agregar" id="sp_add_<?php echo $resul_mod[$i]['cod_submodulo'] ?>">NO</span> 
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td align="center">
                                                                <div class="btn-group" data-toggle="buttons" >
                                                                    <label id="lbl_up_<?php echo $resul_mod[$i]['cod_submodulo'] ?>" class="btn btn-primary btn-sm disabled modificar" >
                                                                        <input type="checkbox" class="modificar" name="modificar[]" id="up_<?php echo $resul_mod[$i]['cod_submodulo'] ?>" value="1"/>
                                                                        <span class="modificar" id="sp_up_<?php echo $resul_mod[$i]['cod_submodulo'] ?>">NO</span> 
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td align="center">
                                                                <div class="btn-group" data-toggle="buttons" >
                                                                    <label id="lbl_del_<?php echo $resul_mod[$i]['cod_submodulo'] ?>" class="btn btn-primary btn-sm disabled eliminar" >
                                                                        <input type="checkbox" class="eliminar" name="eliminar[]" id="del_<?php echo $resul_mod[$i]['cod_submodulo'] ?>" value="1" />
                                                                        <span class="eliminar" id="sp_del_<?php echo $resul_mod[$i]['cod_submodulo'] ?>">NO</span> 
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td align="center">
                                                                <div class="btn-group" data-toggle="buttons" >
                                                                    <label id="lbl_cons_<?php echo $resul_mod[$i]['cod_submodulo'] ?>" class="btn btn-primary btn-sm disabled eliminar" >
                                                                        <input type="checkbox" class="consultar" name="consultar[]" id="con_<?php echo $resul_mod[$i]['cod_submodulo'] ?>" value="1" />
                                                                        <span class="consultar" id="sp_con_<?php echo $resul_mod[$i]['cod_submodulo'] ?>">NO</span> 
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td align="center">
                                                                <div class="btn-group" data-toggle="buttons" >
                                                                    <label id="lbl_imp_<?php echo $resul_mod[$i]['cod_submodulo'] ?>" class="btn btn-primary btn-sm disabled eliminar" >
                                                                        <input type="checkbox" class="imprimir" name="imprimir[]" id="imp_<?php echo $resul_mod[$i]['cod_submodulo'] ?>" value="1" />
                                                                        <span class="imprimir" id="sp_imp_<?php echo $resul_mod[$i]['cod_submodulo'] ?>">NO</span> 
                                                                    </label>
                                                                </div>
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
                                        <td  colspan="4" align="right"><span style="color: #ff0000;margin-left">Campo Obligatorio *</span></td>
                                    </tr>
                                    <tr>
                                        <td  colspan="4" align="center">
                                            <div id="botones">
                                                <input class="btn btn-default btn-sm"  id="btnaccpriv"  name="btnaccpriv"  type="button" value="Agregar" />
                                                <input class="btn btn-default btn-sm"  id="btnlimpriv"  name="btnlimpriv"  type="button" value="Limpiar" />
                                                <input class="btn btn-default btn-sm"  id="btnrestablecer"  name="btnrestablecer"  type="button" value="Restablecer" />
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
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