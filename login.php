<?php
    session_start();
    require 'conexion.php';

    $mensaje = '';
    
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $email = $_POST['email'];
        $contrasena = $_POST['contrasena'];

        if(!empty($email) && !empty($contrasena)){

        }
    }

?>