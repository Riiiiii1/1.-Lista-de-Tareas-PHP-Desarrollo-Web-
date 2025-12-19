<?php
/**
 * Este archivo implementa la actualización de datos mediante Update, en este caso, al ser llamado por un boton editar
 * se captura el id actual. Primero se lee el id, luego se vuelve a cargar el nuevo dato en el campo.
 * NUEVO: Integramos el nuevo sistema de SESSION, y aparte consultar si el usuario es administrador o no.
 */
    session_start();
    require 'config/conexion.php';
    require_once 'classes/Tarea.php';
    // Step 1: Obtener el id actual de la tarea seleccionada (GET)
    $id = $_GET['id'] ?? null;
    if(!$id){                                   //Si no existe id, devolver a index.php, usando header.
        header('Location: index.php');      
        exit;
    }
    // Obtener los datos del usuario loggeado, si tiene rol y si le pertenece la tarea con user_id de la tabla tareas.
    $user_id = $_SESSION['user_id'] ?? null;
    $rol = $_SESSION['rol'] ?? null;
    if(!isset($user_id)){
        header('Location: index.php');  
        exit('Usted No esta Loggeado');
    }
    $tareas = new Tarea($pdo);
    // Step 2:  Si el metodo request es igual a INSERCION (POST) y se pulsa Actualizar ejecuta lo siguientE
    if($_SERVER['REQUEST_METHOD']== 'POST'){
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];   
            $tareas->editar($titulo,$descripcion,$id,$rol,$user_id);
            header('Location: index.php');  // Redireccionar a la pagina principal (READ)
            exit;

    }
    $tarea = $tareas ->obtenerPorId($id,$user_id,$rol);
    if(!$tarea){
        die('La Tarea que busca no se encuentra en la base de datos.');
    }
    require 'views/edit.view.php';
?>

