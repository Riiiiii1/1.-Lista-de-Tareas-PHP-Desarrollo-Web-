<?php
/**
 *  Toggle o Endpoint simulado que funciona con un checkbox para enmarcar o desemarcar si una tarea esta completa o no.
 *  Pero asumiendo que este archivo tome y envie siempre en formato json y que sea llamado por fech() desde la vista.
 */
session_start();
require '../config/conexion.php'; // Nota el '../' para salir de la carpeta api

// Step 1:  Configuramos la respuesta para que sea JSON
header('Content-Type: application/json');

// Step 2:  Solo acepta usuarios logueados
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit();
}

// Step 3: Leemos los datos enviados por JavaScript (JSON Body)
// Convierte los datos en un array asociativo
$input = json_decode(file_get_contents('php://input'), true);
$id_tarea = $input['id'] ?? null;

if ($id_tarea) { 
    try { // Nuevo : Segun el rol del usuario que quiere modificar lista un sql diferente.
        if ($_SESSION['rol'] === 'admin') {
            $sql = "UPDATE tareas SET completada = NOT completada WHERE id = :id";
            $params = [':id' => $id_tarea];
        } else {
            $sql = "UPDATE tareas SET completada = NOT completada WHERE id = :id AND user_id = :user_id";
            $params = [':id' => $id_tarea, ':user_id' => $_SESSION['user_id']];
        }
        $statement = $pdo->prepare($sql);
        $statement->execute($params);
        // Devolvemos respuesta de éxito
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Error de BD']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Falta ID']);
}
?>