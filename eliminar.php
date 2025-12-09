<?php
    /**
    * Este archivo implementa la logica para eliminar un registro, que simplemente se llama a la logica y no a la vista.
    * Se usa una sentencia para eliminar el registro desde una id, primero listando el id como en editar.php.
    * y finalmente eliminarlo con un DELETE/WHERE.
    */

    // Step 1: Listar el id, y simplemente ejecutar un statement con una sentencia cargada.
    require 'conexion.php';
    $id = $_GET['id'] ?? null;
    if (!$id) {
        header('Location: index.php');
        exit;
    }
    try{
        $sql = "DELETE FROM tareas WHERE id = :id";
        $statement = $pdo ->prepare($sql);
        $statement->execute([
            ':id' =>$id
        ]);
        header('Location: index.php'); // Despues de borrar redireccionar
        exit;
    }catch(PDOException $e){
        die('Error al Eliminar'. $e->getMessage());
    }

    
?>