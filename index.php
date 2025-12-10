<?php
    /**
     * Archivo encargado de ejecutar sentencias como 'POST' para enviar datos a la base de datos, usando conexion.php
     * como punto de enlace y autenticacion.
     */

    require 'conexion.php';
    $mensaje = "";
    $tipoSentencia = "";
    // Step 1:  Si el metodo request es igual a INSERCION (POST) ejecuta lo siguiente. 

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $tipoSentencia = 'POST';
        $titulo = $_POST['titulo'];                     // Recibir las variables desde el formulario.
        $descripcion = $_POST['descripcion'];  
        if(!empty($titulo)){                            // Validación para que no este vacio.
            $sql = "INSERT INTO tareas (titulo,descripcion) VALUES (:titulo, :descripcion)";   // Consulta SQL 
            $statement = $pdo->prepare($sql);     // Preparar sentencia llamando a la funciOn prepare(), pasando $sql
            if($statement->execute([':titulo'=>$titulo, ':descripcion'=>$descripcion])){ //Si se ejecuta, envia true
                $mimensaje  = "Sentencia " . $tipoSentencia . "ejecutada con exito.";
            }else{
                $mimensaje = "Sentencia " . $tipoSentencia . "fallo.";
            }
        }else{
            $mensaje = "El titulo es obligatorio";
        }
    }
    /**
     * Tener en cuenta es un listado inmediato, sin pasar ningun parametro por lo que usamos query() 
     * Si se utilizara query(), en una consulta con parametros, hay riesgo de una inyeccion sql.
     */
    $statement = $pdo->query("SELECT * FROM tareas ORDER BY creado_en DESC");  //Definir una variable con una sentencia,
    $tareas = $statement ->fetchAll(PDO::FETCH_ASSOC);                          //Extraer todas las filas en un array.


    /**
     * MEDIDA DE SEGURIDAD BASICA IMPORTANTE CONTRA XSS:
     * Para evitar inyecciones sql, al ingresar una tarea y al volver a cargar esa tarea, el navegador interpreta el codigo.
     * Si es que no se ejecuto una medida de proteccion. Para ello se usa htmlspecialchars para evitar que el navegador cargue 
     * codigo malisioso al cargar el html. 
     */
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Tareas</title>
</head>
<body>
    <h1>Mis Tareas</h1>
    <ul>
        <?php foreach ($tareas as $tarea): ?>
            <li>
                <strong><?= htmlspecialchars($tarea['titulo']) ?></strong> 
                
                <?php if ($tarea['completada']): ?>
                    <span style="color: green;">(Completada)</span>
                <?php else: ?>
                    <span style="color: red;">(Pendiente)</span>
                <?php endif; ?>
                
                <a href="editar.php?id=<?= $tarea['id'] ?>">Editar</a>
                <a href="eliminar.php?id=<?= $tarea['id'] ?>" 
                onclick="return confirm('¿Estás seguro de que quieres borrar esta tarea?');"
                style="color: red;">Eliminar
                </a>
            </li>
        <?php endforeach; ?>


    </ul>
    
    <?php if ($mensaje): ?>
        <p style="color: blue;"><strong><?= $mensaje ?></strong></p>
    <?php endif; ?> 

    <form action="index.php" method="POST">
        <label>Título:</label><br>
        <input type="text" name="titulo" required><br><br>
        
        <label>Descripción:</label><br>
        <textarea name="descripcion"></textarea><br><br>
        
        <button type="submit">Guardar Tarea</button>
    </form>
</body>
</html>