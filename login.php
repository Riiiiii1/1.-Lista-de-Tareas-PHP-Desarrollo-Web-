<?php
    /**
     * Este archivo, tiene la logica de loggeo mediante $_SESSION, para ello se debe inicializar cada archivo con session_start().
     * Toda ruta protegida debe iniciar session_start();
     */
    session_start();
    require 'config/conexion.php';

    $mensaje = '';
    
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $email = $_POST['email'];
        $contrasena = $_POST['contrasena'];
        if(!empty($email) && !empty($contrasena)){
            // Step 1: Listar el registro del usuario por email (Lo habiamos puesto unique por algo) para la comparacion.
             /**Recordar que esta consulta lista el registro completo , no devuelve solo el registro con la columna email*/
            $sql = "SELECT * FROM usuarios WHERE email = :email";                                                                 
            $statement = $pdo ->prepare($sql);
            $statement ->execute([':email'=> $email ]);
            $usuario = $statement->fetch(PDO::FETCH_ASSOC);     // Obtenemos el registro del usuario usando fech().

            // Step 2: Verifica si el usuario no es nullo, y si la contraseña es correcta enviando como parametro la
            // contraseña y la contraseña hasheada para descifrar.
            /** Para acceder a una columna especifica del registro del usuario se utiliza $usuario + (Nombre de la colum.) */


            if($usuario && password_verify($contrasena, $usuario['contrasena'])){

                // LOGIN CORRECTO 
                // Step 3: Regenerar un ID de sesión. Esto para evitar "Session Fixation"
                session_create_id();
                // Step 4: Guardar los datos de la sesion iguales al id del usuario y al email del mismo.
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['rol'] = $usuario['rol'];  // NUEVO : Definir un Rol, en este caso tambien se envia el rol si coincide con el email del admin.
                header('Location: index.php');
                exit;
            }else{
                $mensaje = "Email o contraseña incorrectos.";
            }

        }else{
            $mensaje = 'Verifique que los campos esten llenos';
        }
    }
    require 'views/login.view.php';
?>
