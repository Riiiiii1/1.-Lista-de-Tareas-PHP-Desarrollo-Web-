<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Tarea</title>
</head>
<body>
    <h1>Editar Tarea</h1>
    
    <form method="POST">
        <label>Título:</label><br>
        <input type="text" name="titulo" value="<?= htmlspecialchars($tarea['titulo']) ?>" required><br><br>
        
        <label>Descripción:</label><br>
        <textarea name="descripcion"><?= htmlspecialchars($tarea['descripcion']) ?></textarea><br><br>
        
        <button type="submit">Actualizar</button>
        <a href="index.php">Cancelar</a>
    </form>
</body>
</html>