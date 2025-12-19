<?php
/**
 * POO en Desarrollo Web : Al igual que en Laravel, y Spring Boot es eficiente y efecto empaquetar modelos y controladores 
 * para cada función del servidor. En este caso creamos una clase Database, con una función publica que puede ser llamado
 * por otras clases y ejecutarlas.
 */
class Database {
    private $host = 'localhost';
    private $dbname = 'tareas_php';
    private $username ='root';
    private $pass = '';
    private $charset='utf8mb4';


    public function conectar(){
        try{
            $dsn = "mysql:host=" . $this->host . ";dbname=".$this->dbname .";charset=" .$this->charset;
            $opciones = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                     PDO::ATTR_EMULATE_PREPARES => true,]; // Modo emulado activado por el error 
            $pdo = new PDO($dsn,$this->username, $this->pass,$opciones);
            return $pdo;
        }catch(PDOException $e){
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
?>