<?php
    /**
     * Archivo encargado de ejecutar sentencias como 'POST' para enviar datos a la base de datos, usando conexion.php
     * como punto de enlace y autenticacion.
     */

    session_start(); // Iniciamos la sesión para poder leer $_SESSION
    require 'config/conexion.php';
    $user_id = $_SESSION['user_id'] ?? null; 
    // Determinar si en la $_SESSION se encuentra user_id, si no es asi, lo devuelve a login.
    if (!isset($user_id)) {
        header('Location: login.php');
        exit;
    }
    $rol = $_SESSION['rol'] ?? null; // NUEVO: Tras un login, si existe una variable guardada en rol, entonces lo definimos.
    
    // NUEVO : Integración de un sistema de busqueda de tareas.
    $busqueda = $_GET['busqueda'] ?? null;
    $params = [];
    $search_sql = "";
    /**
     *Importante : en php para poder hacer diferentes consultar, se utiliza el %%, como ejemplo podemos hacer asi %tarea1%
     * %tarea1% = Es para buscar la palabra tarea1 a cualquier parte. 
     * tarea1% = Es para buscar un texto que empieze por tarea1.
     * %tarea1 = Es cuando termine en tarea1.
     * En cuanto a $$params[':busqueda'] = "%$busqueda%";  estamos definiendo un placeholder, un marcador para esta varible.
     * $params = [
     * ':busqueda' => '%login%'
     * ];

     */
    
    if($busqueda){
        $search_sql = " AND (titulo LIKE :busqueda OR descripcion LIKE :busqueda)"; 
        $params[':busqueda'] = "%$busqueda%";  // Cuando el sql vea el marcador :busqueda, usa la variable %busqueda%
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
                ':user_id'=>$user_id   // NUEVO : Enviar a que usuario pertenece esa tarea
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
     * NUEVO : Agregamos la logica de busqueda, en este caso, si es que se pulso busqueda se va a listar
     * con los parametros de $search_sql.
     * Aparte, definimos parametros, si es que no existe ninguno, entonces se lista todo para los administradores.
     */
    if ($rol === 'admin') {
        // Admin : Traer todo + el email del dueño de la tarea
        // Aquí introducimos INNER JOIN: Unimos la tabla tareas con usuarios
        $sql = "SELECT tareas.*, usuarios.email as autor 
                FROM tareas 
                INNER JOIN usuarios ON tareas.user_id = usuarios.id  WHERE 1=1 $search_sql 
                ORDER BY creado_en DESC";
                // Usamos WHERE 1=1, debido a que es la forma de poner AND. (CONSULTA MAL HECHA CON QUERY)
    } else {
        // Usuarios : Traer sus tareas.
        $sql = "SELECT * FROM tareas WHERE user_id = :user_id $search_sql  ORDER BY creado_en DESC";
        $params[':user_id'] = $user_id; // Marcador :user_id es igual a user_id de SESSION.
    }

        $statement = $pdo->prepare($sql);
        $statement->execute($params); 
        $tareas = $statement->fetchAll(PDO::FETCH_ASSOC);
    /**
     * MEDIDA DE SEGURIDAD BASICA IMPORTANTE CONTRA XSS:
     * Para evitar inyecciones sql, al ingresar una tarea y al volver a cargar esa tarea, el navegador interpreta el codigo.
     * Si es que no se ejecuto una medida de proteccion. Para ello se usa htmlspecialchars para evitar que el navegador cargue 
     * codigo malisioso al cargar el html. 
     */
    // CARGAR
    require 'views/index.view.php';
?>

