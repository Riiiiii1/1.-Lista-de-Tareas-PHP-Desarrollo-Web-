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
    $user_id = $_SESSION['user_id'];
    try{
        $sql = "DELETE FROM tareas WHERE id = :id AND user_id = :user_id";
        $statement = $pdo ->prepare($sql);
        $statement->execute([
            ':id' =>$id,
            ':user_id' => $user_id  //NUEVO : Integramos y enviamos el user_id para que solo el usuario que lo creo, lo borre.
        ]);
        header('Location: index.php'); // Despues de borrar redireccionar
        exit;
    }catch(PDOException $e){
        die('Error al Eliminar'. $e->getMessage());
    }

    
?>