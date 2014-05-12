<?php 
session_start();
if(isset($_SESSION['menu'])){

   $menu = $_SESSION['menu'];
}else{
    $menu='index1.php';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Página Principal</title>
        <link rel="shortcut icon"   href="imagenes/sistema/favicon.ico" type="image/x-icon" />
        <style type="text/css">
            html, body, div, object { margin:0; padding:0; height:100%;background-color:transparent; }
            html { overflow:hidden; }
            object {display:block; width:100%; border:none; background-color:transparent;}
        </style>   
    </head>
    <body>  
        <object type="text/html" data="<?php echo $menu; ?>"></object>
    </body>
</html>