<?php
define('BASEPATH', '');
require_once 'class.ezpdf.php';
require '../../librerias/globales.php';
require_once '../../modelo/cita/Asignar.php';
$obj = new Asignar();



if (isset($_GET['cedula_p'])) {
    $datos['cedula_p'] = $_GET['cedula_p'];
    $cedula_p          = $_GET['cedula_p'];
}


$data['sql'] = "SELECT 
                    p.nombre,
                    p.apellido,
                    p.historia,
                    CONCAT_WS('-', CONCAT('0',(SELECT codigo FROM codigo_telefono WHERE cod_telefono=p.cod_telefono)),p.telefono) AS telefono,
                    CONCAT_WS('-', CONCAT('0',(SELECT codigo FROM codigo_telefono WHERE cod_telefono=p.cod_celular)),p.celular) AS celular
                FROM paciente AS p
                WHERE CONCAT_WS('-',p.nacionalidad,p.cedula_p) = '$cedula_p'";
$result_paci = $obj->getPaciente($data);

$datos['sql'] = "SELECT 
                    DATE_FORMAT(c.fecha,'%d/%m/%Y') AS fecha,
                    (SELECT consultorio FROM consultorio WHERE num_consultorio=c.num_consultorio) AS consultorio,
                    c.turno 
                FROM cita AS c
                WHERE CONCAT_WS('-',c.nacionalidad,c.cedula_p) = '$cedula_p' ORDER BY 1 LIMIT 1";
$resultado    = $obj->getCita($datos);

$nombres   = $result_paci[0]['nombre'] . ' ' . $result_paci[0]['apellido'];
$telefonos = $result_paci[0]['telefono'] . ' / ' . $result_paci[0]['celular'];
$historia  = $result_paci[0]['historia'];


$fecha       = $resultado[0]['fecha'];
$consultorio = $resultado[0]['consultorio'];
$turno       = $resultado[0]['fecha'];
$turno       = $turno == 1 ? 'MA&Ntilde;ANA' : 'TARDE';
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap_theme; ?>"/>
        <script src="<?php echo _ruta_librerias_js . _js_jquery; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap; ?>" type="text/javascript"></script>
        <style type="text/css" media="print">
            @page{
                /*margin: 0;*/
            }
        </style>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#btnimprimir').click(function() {
                    $(this).css('display', 'none');
                    window.print();
                });
            });
        </script>
    </head>
    <body>
      <table width="200" border="0" align="center">
        <tr>
            <td align="center"><table width="573" border="0" align="center" style="border: 1px dotted #000000">
              <thead>
                <tr>
                  <th colspan="2" align="center">&nbsp;</th>
                </tr>
                <tr>
                  <th colspan="2" align="center">&nbsp;</th>
                </tr>
                <tr>
                  <th colspan="2"  style="text-align:center;font-size: 15px;">Maternidad Integral de Aragua</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th height="23" colspan="2" style="text-align:center;font-size: 15px;">Control de Citas</th>
                </tr>
                <tr>
                  <th width="116" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nombre y Apellido:</th>
                  <td width="216"><?php echo $nombres; ?></td>
                </tr>
                <tr>
                  <th align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Telefonos:</th>
                  <td><?php echo $telefonos; ?></td>
                </tr>
                <tr>
                  <th align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nro. de Historia:</th>
                  <th><?php echo $historia; ?></th>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2" style="text-align:center"><table border="0" style="width: 100%">
                    <thead>
                      <tr>
                        <th width="31%" style="text-align:  center">Fecha de Consulta</th>
                        <th width="51%" style="text-align:  center">Consultorio</th>
                        <th width="18%" style="text-align:  center">Turno</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td height="49" style="text-align:  center;"><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto"><?php echo $fecha; ?></div></td>
                        <td style="text-align:  center"><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto"><?php echo $consultorio; ?></div></td>
                        <td style="text-align:  center"><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto"><?php echo $turno; ?></div></td>
                      </tr>
                      <tr>
                        <td height="37"><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto"><span style="margin-left: -20px; padding: 10px;">/</span><span>&nbsp;</span>/</div></td>
                        <td><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto">&nbsp;</div></td>
                        <td><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto">&nbsp;</div></td>
                      </tr>
                      <tr>
                        <td height="37"><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto"><span style="margin-left: -20px; padding: 10px;">/</span><span>&nbsp;</span>/</div></td>
                        <td><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto">&nbsp;</div></td>
                        <td><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto">&nbsp;</div></td>
                      </tr>
                      <tr>
                        <td height="37"><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto"><span style="margin-left: -20px; padding: 10px;">/</span><span>&nbsp;</span>/</div></td>
                        <td><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto">&nbsp;</div></td>
                        <td><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto">&nbsp;</div></td>
                      </tr>
                      <tr>
                        <td height="37"><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto"><span style="margin-left: -20px; padding: 10px;">/</span><span>&nbsp;</span>/</div></td>
                        <td><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto">&nbsp;</div></td>
                        <td><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto">&nbsp;</div></td>
                      </tr>
                      <tr>
                        <td height="37"><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto"><span style="margin-left: -20px; padding: 10px;">/</span><span>&nbsp;</span>/</div></td>
                        <td><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto">&nbsp;</div></td>
                        <td><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto">&nbsp;</div></td>
                      </tr>
                      <tr>
                        <td height="37"><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto"><span style="margin-left: -20px; padding: 10px;">/</span><span>&nbsp;</span>/</div></td>
                        <td><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto">&nbsp;</div></td>
                        <td><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto">&nbsp;</div></td>
                      </tr>
                      <tr>
                        <td height="37"><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto"><span style="margin-left: -20px; padding: 10px;">/</span><span>&nbsp;</span>/</div></td>
                        <td><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto">&nbsp;</div></td>
                        <td><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto">&nbsp;</div></td>
                      </tr>
                      <tr>
                        <td height="37"><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto"><span style="margin-left: -20px; padding: 10px;">/</span><span>&nbsp;</span>/</div></td>
                        <td><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto">&nbsp;</div></td>
                        <td><div style="border-bottom:1px dotted #000000;width: 70%;margin: auto">&nbsp;</div></td>
                      </tr>
                    </tbody>
                    <tbody>
                    </tbody>
                    <tbody>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td colspan="2" align="center"></td>
                </tr>
                <tr>
                  <td colspan="2">&nbsp;</td>
                </tr>
              </tbody>
          </table></td>
        </tr>
          <tr>
            <td height="54" align="center">
              <input class="btn btn-default btn-sm" id="btnimprimir" name="btnimprimir" type="button" value="Imprimir"/></td>
          </tr>
      </table>
    </body>
</html>