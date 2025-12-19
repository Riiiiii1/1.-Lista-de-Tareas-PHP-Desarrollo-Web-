<?php
/**
 * En este archivo, se encontraba la conexión a la base de datos, pero ahora llamamos a Database.php y su función para ejecutarse.
 * Sin romper los otros archivos.
 */
    // __DIR__ es para que PHP busque la ruta relativa a la carpeta
    require_once __DIR__ . '/../classes/Database.php';

    // Instanciar la clase, y creamos el objeto. 
    $db = new Database();
    $pdo = $db -> conectar();

?>