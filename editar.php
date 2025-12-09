<?php
/**
 * Este archivo implementa la actualización de datos mediante Update, en este caso, al ser llamado por un boton editar
 * se captura el id actual. Primero se lee el id, luego se vuelve a cargar el nuevo dato en el campo.
 */
    require 'conexion.php';
    // Step 1: Obtener el id actual de la tarea seleccionada (GET)
    $id = $_GET['id'] ?? null;
    if(!$id){                                   //Si no existe id, devolver a index.php, usando header.
        header('Location: index.php');      
        exit;
    }
    // Step 2:  Si el metodo request es igual a INSERCION (POST) y se pulsa Actualizar ejecuta lo siguientE
    if($_SERVER['REQUEST_METHOD']== 'POST'){
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];   
        $sql = "UPDATE tareas SET titulo = :titulo, descripcion = :descripcion WHERE id = :id";
    }

?>