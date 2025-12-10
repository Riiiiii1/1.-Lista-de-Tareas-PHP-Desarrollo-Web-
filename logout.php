<?php
/**
 * Archivo para hacer terminar una sesión. Primero se debe inicializar session_start().
 * Luego debemos vaciar las variables guardadas en la sesion.
 * Utilizamos session_destroy() para finalizar la sesión y devolver al login.
 */


    session_start();
    $_SESSION = [];
    session_destroy();
    header('Location: login.php');
    exit;

    
?>