<?php
    /**
    * Este archivo implementa la logica para eliminar un registro, que simplemente se llama a la logica y no a la vista.
    * Se usa una sentencia para eliminar el registro desde una id, primero listando el id como en editar.php.
    * y finalmente eliminarlo con un DELETE/WHERE.
    * NUEVO:  Integración de Sesión para que solo el usuario pueda eliminar.
    */
    session_start(); // NUEVO :Iniciamos la sesión para poder leer $_SESSION.
    // Step 1: Listar el id, y simplemente ejecutar un statement con una sentencia cargada.
    require 'config/conexion.php';
    require_once 'classes/Tarea.php';
    $id = $_GET['id'] ?? null;
    if (!$id) {
        header('Location: index.php');
        exit;
    }
    $rol = $_SESSION['rol'] ?? null; 
    $user_id = $_SESSION['user_id'] ?? null;

    $tarea = new Tarea($pdo);
    $tarea ->eliminar($rol,$user_id,$id);
    header('Location: index.php');
?>