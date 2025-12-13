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
    $id = $_GET['id'] ?? null;
    if (!$id) {
        header('Location: index.php');
        exit;
    }
    $rol = $_SESSION['rol'] ?? null; 
    $user_id = $_SESSION['user_id'] ?? null;
        // Para superusuarios o admin   
    if($rol == 'admin'){ // NUEVO: CODICIONAL QUE VERIFICA QUE EL USUARIO ES ADMINISTRADOR O NO, SI ES ADMINISTRADOR, ENTONCES
                        // PUEDE BORRAR UNA TAREA.
        $sql = "DELETE FROM tareas WHERE id = :id";  // Eliminar por el id.
        $params = [':id' => $id];
    }else{
        // Para usuarios normales
        $sql = "DELETE FROM tareas WHERE id = :id AND user_id = :user_id"; // Eliminar por id, y por user_id, si pertenece a esa tarea.
        $params = [':id' => $id, ':user_id' => $user_id];
    }
    try{
        $statement =$pdo->prepare($sql);
        $statement->execute($params);
        header('Location: index.php'); // Despues de borrar redireccionar
        exit;
    }catch(PDOException $e){
        die('Error al Eliminar'. $e->getMessage());
    }

?>