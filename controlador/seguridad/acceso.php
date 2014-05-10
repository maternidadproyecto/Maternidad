<?php
session_start();
$perfil = $_SESSION['perfil'];
header('Location:../../menu_priv.php');
