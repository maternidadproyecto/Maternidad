<?php 
if (!isset($_SESSION['sesion_id']) && !isset($_SESSION['token'])) {
    exit(utf8_decode("<div style='color:#FF0000;text-align:center;margin:0 auto'>La Sessión Expiro debe iniciar sessión nuevamente</div>"));
}
?>
