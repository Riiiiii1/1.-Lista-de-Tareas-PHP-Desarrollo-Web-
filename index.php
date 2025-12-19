<?php
    /**
     * Archivo encargado de ejecutar sentencias como 'POST' para enviar datos a la base de datos, usando conexion.php
     * como punto de enlace y autenticacion.
     */

    session_start(); // Iniciamos la sesión para poder leer $_SESSION
    require 'config/conexion.php';
    require_once 'classes/Tarea.php'; // Importar el modelo Tarea.php
    
    $tarea = new Tarea($pdo); // Crear el objeto tarea pasandole la conexión a la base de datos para las consultas.
    $user_id = $_SESSION['user_id'] ?? null; 
    // Determinar si en la $_SESSION se encuentra user_id, si no es asi, lo devuelve a login.
    if (!isset($user_id)) {
        header('Location: login.php');
        exit;
    }
    $rol = $_SESSION['rol'] ?? null; // NUEVO: Tras un login, si existe una variable guardada en rol, entonces lo definimos.
    // NUEVO : Integración de un sistema de busqueda de tareas.
    $busqueda = $_GET['busqueda'] ?? null;
    
    // Step 1:  Si el metodo request es igual a INSERCION (POST) ejecuta lo siguiente. 
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $titulo = $_POST['titulo'];                     // Recibir las variables desde el formulario.
        $descripcion = $_POST['descripcion'];  
        if(!empty($titulo)){                            // Validación para que no este vacio.

            if($tarea ->crear($titulo,$descripcion,$user_id)){
                header("Location: index.php?ok=1");  // IMPORTANTE : Al momento de recargar la pagina, se realiza una nueva tarea.
                                                            // Para evitarlo, se debe cortar el flujo POST , mediante un Ok.
                                                            // Esto se llama : Form Resubmission
                exit;
            }
        } 
    }
    $tareas = $tarea ->listar($user_id,$busqueda,$rol);

    require 'views/index.view.php';
?>

