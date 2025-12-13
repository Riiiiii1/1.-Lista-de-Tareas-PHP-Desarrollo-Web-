<?php
/**
 * Este archivo implementa la actualización de datos mediante Update, en este caso, al ser llamado por un boton editar
 * se captura el id actual. Primero se lee el id, luego se vuelve a cargar el nuevo dato en el campo.
 * NUEVO: Integramos el nuevo sistema de SESSION, y aparte consultar si el usuario es administrador o no.
 */
    session_start();
    require 'config/conexion.php';
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

    // Step 2:  Si el metodo request es igual a INSERCION (POST) y se pulsa Actualizar ejecuta lo siguientE
    if($_SERVER['REQUEST_METHOD']== 'POST'){
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];   
 
        if($rol == 'admin'){ // VERIFICAR SI ES ADMINISTRADOR
            $sql_update = "UPDATE tareas SET titulo = :titulo, descripcion = :descripcion WHERE id = :id";
            $params = [':titulo'=>$titulo,':descripcion'=>$descripcion, ':id'=>$id ];
        }else{
            $sql_update = "UPDATE tareas SET titulo = :titulo, descripcion = :descripcion WHERE id = :id AND user_id = :user_id";
            $params = [':titulo'=>$titulo,':descripcion'=>$descripcion,':id'=>$id, ':user_id'=>$user_id];
        }
        try{
            $statement = $pdo->prepare($sql_update);
            $statement->execute($params);
            header('Location: index.php');  // Redireccionar a la pagina principal (READ)
            exit;
        }catch(PDOException $e){
            die('La actualización fallo' . $e->getMessage());
        }
    }
    // Step 3: Incluimos una funcion de listar en el formulario (inputs), para que el usuario vea el registro anterior. 
    $statement = $pdo->prepare('SELECT * FROM tareas WHERE id = :id');  // Preparar la sentencia para listar con select
    $statement ->execute([ ':id' => $id ]);                             // Cargar el unico parametro que es el id.
    $tarea = $statement ->fetch(PDO::FETCH_ASSOC);                     // Fech ya que solo se espera un registro no un array.
    if(!$tarea){
        die('La Tarea que busca no se encuentra en la base de datos.');
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Tarea</title>
</head>
<body>
    <h1>Editar Tarea</h1>
    
    <form method="POST">
        <label>Título:</label><br>
        <input type="text" name="titulo" value="<?= htmlspecialchars($tarea['titulo']) ?>" required><br><br>
        
        <label>Descripción:</label><br>
        <textarea name="descripcion"><?= htmlspecialchars($tarea['descripcion']) ?></textarea><br><br>
        
        <button type="submit">Actualizar</button>
        <a href="index.php">Cancelar</a>
    </form>
</body>
</html>