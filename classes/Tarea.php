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

    //Metodo Listar, para mostrar las tareas en función del usuario listado, la busqueda y el rol. 

    public function listar($user_id, $busqueda, $rol){
        $params =[];
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
        /**
         * Tener en cuenta es un listado inmediato, sin pasar ningun parametro por lo que usamos query() 
         * Si se utilizara query(), en una consulta con parametros, hay riesgo de una inyeccion sql.
         * NUEVO : Agregamos la logica de busqueda, en este caso, si es que se pulso busqueda se va a listar
         * con los parametros de $search_sql.
         * Aparte, definimos parametros, si es que no existe ninguno, entonces se lista todo para los administradores.
         */
        if($rol =='admin'){
            // Admin : Traer todo + el email del dueño de la tarea
            // Aquí introducimos INNER JOIN: Unimos la tabla tareas con usuarios
            $sql = "SELECT tareas.*, usuarios.email as autor 
                    FROM tareas 
                    INNER JOIN usuarios ON tareas.user_id = usuarios.id 
                    WHERE 1=1 $search_sql
                    ORDER BY creado_en DESC";
            
        }else{
            $sql = "SELECT * FROM tareas 
                    WHERE user_id = :user_id $search_sql 
                    ORDER BY creado_en DESC";
            $params[':user_id'] = $user_id;// Marcador :user_id es igual a user_id de SESSION.
        }

        try{

        /**
         * IMPORTANTE! : Existe un error de parametro, cuando se quiere buscar por una sola variable, la base de datos espera
         * dos. Por ello, la solución a este problema seria agregar las dos variables titulo y descripcion. Pero algo mas 
         * facil seria habilitar el modo simulado. En este caso hicimos el modo simulado. De todas formas, este problema se 
         * corrige en frameworks de alto nivel.
         */
            //Referenciar al pdo con la conexión cargada.
            $statement = $this->pdo->prepare($sql);
            $statement ->execute($params);
            return $statement -> fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e) {
            return []; // Como va a devolver un objeto array porque se hace fechAll, entonces solo lo dejamos vacio si sale error.
        }

    }
    public function crear($titulo,$descripcion,$user_id){
        $sql = "INSERT INTO tareas (titulo,descripcion,user_id) VALUES (:titulo, :descripcion, :user_id)"; 
        $statement = $this ->pdo ->prepare($sql);
        return $statement -> execute([
            ':titulo' => $titulo,
            ':descripcion'=> $descripcion,
            ':user_id'=>$user_id
        ]);
    }
    public function eliminar($rol,$user_id,$id){
        if($rol == "admin"){
            $sql = "DELETE FROM tareas WHERE id = :id";  // Eliminar por el id.
            $params = [':id' => $id];
        }else{
            $sql = "DELETE FROM tareas WHERE id = :id AND user_id = :user_id"; // Eliminar por id, y por user_id, si pertenece a esa tarea.
            $params = [':id' => $id, ':user_id' => $user_id];
        }
        $statement = $this->pdo ->prepare($sql);
        return $statement -> execute($params);
    }

    public function editar($titulo,$descripcion,$id, $rol, $user_id){
        if($rol == "admin"){
            $sql = "UPDATE tareas SET titulo = :titulo, descripcion = :descripcion WHERE id =:id";
            $params = [':titulo'=>$titulo,':descripcion'=>$descripcion, ':id'=>$id ];
        }else{
            $sql = "UPDATE tareas SET titulo = :titulo, descripcion = :descripcion WHERE id = :id AND user_id = :user_id";
            $params = [':titulo'=>$titulo,':descripcion'=>$descripcion,':id'=>$id, ':user_id'=>$user_id];
        }
        $statement = $this ->pdo ->prepare($sql);
        return $statement ->execute($params);
    }
    public function obtenerPorId($id, $user_id, $rol){
        if ($rol === 'admin') {
            $sql = "SELECT * FROM tareas WHERE id = :id";
            $params = [':id' => $id];
        } else {
            $sql = "SELECT * FROM tareas WHERE id = :id AND user_id = :user_id";
            $params = [':id' => $id, ':user_id' => $user_id];
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>