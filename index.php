<?php
    /**
     * Archivo encargado de ejecutar sentencias como 'POST' para enviar datos a la base de datos, usando conexion.php
     * como punto de enlace y autenticacion.
     */

    session_start(); // Iniciamos la sesión para poder leer $_SESSION
    require 'config/conexion.php';
    // Determinar si en la $_SESSION se encuentra user_id, si no es asi, lo devuelve a login.
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    $usuario = "";
    $mensaje = "";
    $tipoSentencia = "";
    // Step 1:  Si el metodo request es igual a INSERCION (POST) ejecuta lo siguiente. 

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $tipoSentencia = 'POST';
        $titulo = $_POST['titulo'];                     // Recibir las variables desde el formulario.
        $descripcion = $_POST['descripcion'];  
        if(!empty($titulo)){                            // Validación para que no este vacio.
            $sql = "INSERT INTO tareas (titulo,descripcion,user_id) VALUES (:titulo, :descripcion, :user_id)";   // Consulta SQL 
            $statement = $pdo->prepare($sql);     // Preparar sentencia llamando a la funciOn prepare(), pasando $sql
            if($statement->execute(
                [':titulo'=>$titulo, 
                ':descripcion'=>$descripcion, 
                ':user_id'=>$_SESSION['user_id']   // NUEVO : Enviar a que usuario pertenece esa tarea
                ])){ //Si se ejecuta, envia true
                $mimensaje  = "Sentencia " . $tipoSentencia . "ejecutada con exito.";
                header("Location: index.php?ok=1");  // IMPORTANTE : Al momento de recargar la pagina, se realiza una nueva tarea.
                                                            // Para evitarlo, se debe cortar el flujo POST , mediante un Ok.
                                                            // Esto se llama : Form Resubmission
                exit;
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
    $sql = "SELECT * FROM tareas WHERE user_id = :user_id ORDER BY creado_en DESC";
    $statement = $pdo->prepare($sql);  //NUEVO :  Usamos prepare porque es una lista contralada de tareas en funcion del usuario.
    $statement -> execute([
        ':user_id'=>$_SESSION['user_id']
    ]);
    $tareas = $statement ->fetchAll(PDO::FETCH_ASSOC);   //Extraer todas las filas en un array.
    /**
     * MEDIDA DE SEGURIDAD BASICA IMPORTANTE CONTRA XSS:
     * Para evitar inyecciones sql, al ingresar una tarea y al volver a cargar esa tarea, el navegador interpreta el codigo.
     * Si es que no se ejecuto una medida de proteccion. Para ello se usa htmlspecialchars para evitar que el navegador cargue 
     * codigo malisioso al cargar el html. 
     */
    // CARGAR
    require 'views/index.view.php';
?>

