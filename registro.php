<?php
    /**
     * registro.php es un archivo que simula el registro exitoso de un nuevo usuario en un sistema de autenticacion y
     * seguridad. En esta versión simple, se envia mediante un formulario los dos campos unicos de email y contrasena.
     */

    require 'config/conexion.php';
    $mensaje = "";
    // Step 1:  Si el metodo request es igual a INSERCION (POST) ejecuta lo siguiente. 
    
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $email = $_POST['email'];               // Obtener los datos del formulario, preparandolos para insertarlos.
        $contrasena = $_POST['contrasena'];

        if(!empty($email) && !empty($contrasena)){      // Validar que en el formulario no falte ningun campo de ingresar.
            $contrasena = password_hash($contrasena,PASSWORD_DEFAULT); // Funcion para hashear la contraseña (medida de seguridad).
            $sql = "INSERT INTO usuarios (email, contrasena) VALUES (:email, :contrasena)"; 
            $statement = $pdo->prepare($sql);   // User prepare y no query, ya que enviamos datos.
            try{  
                if($statement -> execute([':email'=> $email,':contrasena'=> $contrasena,])){
                    $mensaje = "Usuario Creado!";
                    header('Location: login.php');
                    exit();
                }
            }catch(PDOException $e){
                if($e->getCode()==23000){    // Si el error es de codigo 23000, quiere decir que en la base de datos ya existe un registro con esos datos.
                    $mensaje = "El Usuario ya esta registrado";
                }else{
                    $mensaje = "Error: " . $e->getMessage();
                }
            }
        }else{
            $mensaje = "Completa todos los campos";
        }
    }
    require 'views/register.view.php';
?>
