<?php
session_start();
date_default_timezone_set('America/Caracas');
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));

require 'librerias/globales.php';
if (isset($_SESSION['id_usuario'])) {
    $id_usuario       = $_SESSION['id_usuario'];
    $usuario          = $_SESSION['usuario'];
    $_SESSION['menu'] = 'menu_priv.php';
}
$sub_modulo = '';
$s_modulo   = '';
if (isset($_SESSION['url'])) {
    $sub_modulo = $_SESSION['url'];
}
if (isset($_SESSION['s_modulo'])) {
    $s_modulo = $_SESSION['s_modulo'];
}
require_once './modelo/seguridad/SubModulo.php';
$obj_submodulo       = new SubModulo();

$datos['id_usuario'] = $id_usuario;
$result_modulos      = $obj_submodulo->getModulo($datos);
?>

<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="shortcut icon"   href="imagenes/sistema/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" href="librerias/css/maquetacion.css"  />
        <link rel="stylesheet" type="text/css" href="librerias/css/apprise.css"  />
        <link rel="stylesheet" type="text/css" href="librerias/css/basic.css"/>
        <script type="text/javascript" src="librerias/js/jquery.1.10.js"></script>
        <script src="librerias/js/jquery.simplemodal.js" type="text/javascript"></script>
        <script type="text/javascript" src="librerias/js/apprise.js"></script>
        <script type="text/javascript" src="librerias/js/ddsmoothmenu.js"></script>
        <style type="text/css">
            div#menu,div#menu > ul > li > ul > li > span{
                font-family:Arial, Helvetica, sans-serif;
                font-size: 11px;
            }
            span#salir{
                padding:9px;
                float: right;
                border-left: 1px solid #778;
                color: #FFFFFF;
                width:53px;
                text-align: center;
                cursor: pointer;
            }
            span#salir:hover{
                background-color:#AA4B97;
            }
            .usuario{
                font-family:Arial, Helvetica, sans-serif;
                font-size: 12px;
                position: relative;
                top:-40px;
                left: 15px;
                color: #670256;
                font-weight: bold;

            }
            iframe {
                width: 100%;
                height: 700px;
                overflow: hidden;
                border: none;
                background-color:transparent;
                display:block;
                margin: auto;
                min-height: 500px;
                /*;width: 100%;height: 100%;min-height: 550px;max-height: 900px;*/
            }
        </style>
        <script type="text/javascript">

            ddsmoothmenu.init({
                mainmenuid: "menu", //menu DIV id
                orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
                //classname: 'ddsmoothmenu', //class added to menu's outer DIV
                customtheme: ["#670256", "#AA4B97"],
                contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
            });
            $(document).ready(function() {
                
                show();
                var sub_modulo = '<?php echo $sub_modulo; ?>';
                var s_modulo = '<?php echo $s_modulo; ?>';
                if (sub_modulo != '') {
                    var cargar = sub_modulo;
                }
                if (s_modulo != '') {
                    $('div#menu > ul > li > span#' + s_modulo).css('background-color', '#AA4B97');
                }

                $('#ifrmcuerpo').attr('src', cargar);
                
                $("div#menu > ul > li > ul > li > span").click(function() {
                    
                    var cargar = $(this).attr('id');
                    var modulo = $(this).parent('li').parent('ul').parent('li');
   
                    var id = $(this).parent('li').attr('id');
     
                    $('div#menu > ul > li > span').css('background-color', '#670256');
                    modulo.children('span').css('background-color', '#AA4B97');
                    //var archivo = $(this).attr('id');

                    cargar = cargar + '?modulo=' + id;

                    $('#ifrmcuerpo').attr('src', cargar);
                });

                $("#salir").click(function() {
                    apprise('&iquest;Esta seguro que desea cerrar la sesi&oacute;n?', {'verify': true, 'textYes': 'Aceptar', 'textNo': 'Cancelar'}, function(r) {
                        if (r) {
                            window.location = 'controlador/seguridad/salir.php';
                        }
                    });
                });
            });
            
            function autofitIframe(id){
                if (!window.opera && document.all && document.getElementById){
                    id.style.height=800+id.contentWindow.document.body.scrollHeight;
                } else if(document.getElementById) {
                    id.style.height=80+id.contentDocument.body.scrollHeight+"px";
                }
            }
            function show(){
                var Digital  = new Date();
                var anio     = Digital.getFullYear();
                var mes      = Digital.getMonth()+1;
                var dia      = Digital.getDate();
                var hora     = Digital.getHours();
                var minutos  = Digital.getMinutes();
                var segundos = Digital.getSeconds();
                var dn="AM"; 
                if (hora>12){
                    dn="PM";
                    hora=hora-12;
                }
                if (hora==0){
                    hora=12;
                }
                if (dia<=9){
                    dia="0"+dia;
                }
                if (mes<=9){
                    mes="0"+mes;
                }
                if (hora<=9){
                    hora="0"+hora;
                }
                if (minutos<=9){
                    minutos="0"+minutos;
                }
                if (segundos<=9){
                    segundos="0"+segundos;
                }
                var fecha        = "Fecha:"+dia+'-'+mes+'-'+anio;
                var tiempo       = " Hora:"+hora+":"+minutos+":"+segundos+" "+dn;
                var fecha_actual = fecha+tiempo;
                $('#fecha_hora').text(fecha_actual);
                setTimeout("show()",1000);
            }
        </script>
    </head>
    <body >
        <div id="contenedor">
            <div id="cabecera">
                <img src="imagenes/sistema/header.png" width="960" height="150" alt="header"/>
                <div class="usuario" style="width:200px;font-family:Arial, Helvetica, sans-serif">Usuario: <?php echo $usuario ?></div>
                <div id="fecha_hora" class="usuario" style="width:250px;font-family:Arial, Helvetica, sans-serif"></div>
            </div>

            <div id="menu" class="ddsmoothmenu" style="margin-top: 3px;">
                <ul>
                    <?php
                    for ($i = 0; $i < count($result_modulos); $i++) {
                        ?>
                        <li>
                            <span  id="<?php echo "m" . $result_modulos[$i]['cod_modulo']; ?>">
                                <?php echo $result_modulos[$i]['modulo'] ?>
                            </span>
                            <?php
                            $datos['cod_modulo'] = $result_modulos[$i]['cod_modulo'];
                            $result_submodulos   = $obj_submodulo->getSubModulo($datos);
                            ?>
                            <ul>
                                <?php
                                for ($j = 0; $j < count($result_submodulos); $j++) {
                                    ?>
                                <li id="<?php echo $result_submodulos[$j]['cod_submodulo'] ?>">
                                        <span  id="<?php echo trim($result_submodulos[$j]['ruta']); ?>">
                                            <?php echo $result_submodulos[$j]['sub_modulo'] ?>
                                        </span>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <span id="salir">Salir</span>
            </div>

            <div id="cuerpo" >
                <iframe align="middle"  id="ifrmcuerpo" name="ifrmcuerpo"  frameborder="0" scrolling="no" style="height: 900px;"></iframe>
            </div>
            <div id="pie">
                <img src="imagenes/sistema/pie.png" width="962" height="70" alt="pie"/>
            </div>
        </div>
    </body>
</html>
