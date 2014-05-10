<?php
session_start();
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));

$_SESSION['url'] = 'vista/seguridad/privilegio.php';
require '../../librerias/globales.php';

require_once '../../modelo/Privilegio.php';
$objmod = new Privilegio();
$result = $objmod->getPrivilegioAll();
?>

<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo '../../'._ruta_librerias_css . _css_estilos; ?>"/>

        <script src ="<?php echo '../../'._ruta_librerias_js . _js_jquery;?>"       type="text/javascript"></script>
        <script src ="<?php echo '../../'._ruta_librerias_js . _js_alphanumeric;?>" type="text/javascript"></script>
        <script src ="<?php echo '../../'._ruta_librerias_js . _js_dataTable;?>"    type="text/javascript"></script>
        <script src ="<?php echo '../../'._ruta_librerias_js . _js_alerts;?>"       type="text/javascript"></script>
        <script src ="<?php echo '../../'._ruta_librerias_js . _js_librerias;?>"    type="text/javascript"></script> 
        <script src ="../../librerias/script/privilegio.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="div_borde" >
            <table width="679" border="0" align="center">
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td width="40">
                        <img src="../../imagenes/sistema/doctor1.png" style="width: 32px;height: 32px;"  alt="Doctor"/>
                    </td>
                    <td width="629">Privilegios del Usuario</td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><hr/></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <form name="frmprivilegio" id="frmprivilegio" method="POST" enctype="multipart/form-data">
                            <table width="610" align="center">
                                <tr>
                                    <td width="185" align="right">Privilegio:</td>
                                    <td width="413">
                                        <input style="width: 220px;" type="text" id="privilegio" name="privilegio" value="" maxlength="20"/>
                                        <span class="obligatorio">*</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td  colspan="2" align="right">
                                        <span style="color: #ff0000;margin-left">Campo Obligatorio *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td  colspan="2" align="center">
                                        <input id="btnaccion" name="btnaccion" type="button" value="Agregar" />
                                        <input id="btnlimpiar" name="btnlimpiar" type="button" value="Limpiar" />
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <table id="tabla" border="0" cellspacing="1"  class="dataTable">
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Privilegio</th>
                                    <th>Modificar</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = 0; $i < count($result); $i++) {
                                    ?>
                                    <tr>
                                        <td><?php echo $result[$i]['codigo_privilegio']; ?></td>
                                        <td><?php echo $result[$i]['privilegio']; ?></td>
                                        <td>
                                            <img class ="modificar"  title="Modificar" style="cursor: pointer" src="<?php echo '../../'._img_datatable . _img_datatable_modificar ?>" width="18" height="18" alt="Modificar"/>
                                        </td>
                                        <td>
                                            <img class ="eliminar"  title="Eliminar" style="cursor: pointer" src="<?php echo '../../'._img_datatable . _img_datatable_eliminar ?>" width="18" height="18"  alt="Eliminar"/>
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
                    <td colspan="2">&nbsp;</td>
                </tr>
            </table>
        </div>
    </body>
</html>