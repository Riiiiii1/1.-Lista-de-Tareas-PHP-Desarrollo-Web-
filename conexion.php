<?php
    /**
     * Para conectar a una base de datos vamos a usar PDOException ya que usar otros metodos inseguros y anticuados como 
     * mysql_connect, aparte de facilitar el uso de Sentencias Preparadas para evitar inyecciones SQL.
     */

    // Step 1: Definir los parametros para la conexion de una base de datos en Laragon

    $host = 'localhost';    // Puerto de la base de datos
    $dbname = 'tareas_php'; // Definir de la base de datos a la que se conectara
    $usuario = 'root';       // Credenciales de acceso  
    $contrasena = '';

    /*
    * Dentro de un bloque de manejos try-catch, hacemos una instancia a la clase PDO, si es que hay un error.
    */
    try{
        // Step 2: Definir un objeto DSN, opciones de sentencias preparadas, y pasarlas por el objeto PDO.
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $opciones =[
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,      //    Activar modo de errores por excepcion
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //    Activar el envio de un arreglo asociativo usando fetch()
            PDO::ATTR_EMULATE_PREPARES => false,              //    Desactiva la emulacion de consultas preparadas
        ];
        $pdo = new PDO($dsn,$usuario,$contrasena,$opciones); //     Crear la instancia 
        echo('Conectado');
    }catch(PDOException $e){

        die('Error en la conexion: ' . $e -> getMessage());
    }

?>