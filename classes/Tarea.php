<?php
/*
* Siguiendo el padron MVC, se crea primero los modelos, que son manejados por los controladores. En este caso para index.php
* 
*/
class Tarea{
    private $pdo;
    // A diferencia de Java, donde se utiliza el mismo nombre de la clase para crear un constructuro, aqui se utiliza __construct().

    // En este caso, vamos a llamar desde otra clase, y para inicializarlo le pasamos la conexión a la base de datos.
    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    //Metodos : 

    public function obtenerTareas($user_id, ){

    }

}
?>