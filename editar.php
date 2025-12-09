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
        $statement = $pdo->prepare($sql);

        try{
            $statement->execute(
                [
                    ':titulo'=>$titulo,             // Insertar el nuevo titulo desde el input
                    ':descripcion'=>$descripcion,   // Insertar nueva descripcion desde el input
                    ':id'=>$id                      // Cargar el id del registro de donde se va a ejecutar.
                ]
            );
            header('Location: index.php');  // Redireccionar a la pagina principal (READ)
            exit;
        }catch(PDOException $e){
            die('La actualización fallo' . $e->getMessage());
        }
    }
    // Step 3: Incluimos una funcion de listar en el formulario (inputs), para que el usuario vea el registro anterior. 
    $statement = $pdo->prepare('SELECT * FROM tareas WHERE id = :id');  // Preparar la sentencia para listar con select
    $statement ->execute([ ':id' => $id]);                             // Cargar el unico parametro que es el id.
    $tarea = $statement -> fetch(PDO::FETCH_ASSOC);                     // Fech ya que solo se espera un registro no un array.
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