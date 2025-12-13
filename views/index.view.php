
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Tareas</title>
</head>
<body>
    <?php if (!$usuario): ?>
        <h1 style="color: blue;"><strong><?= "Bienvenido ,  " . htmlspecialchars($usuario = $_SESSION['email'])?></strong></h1>
    <?php endif; ?> 
    <ul>
        <?php foreach ($tareas as $tarea): ?>
            <li>
                <input type="checkbox" 
                    class="check-tarea" 
                    data-id="<?= $tarea['id'] ?>" 
                    <?= $tarea['completada'] ? 'checked' : '' ?>>
                
                <span id="texto-<?= $tarea['id'] ?>" 
                    style="<?= $tarea['completada'] ? 'text-decoration: line-through;' : '' ?>">
                    <?= htmlspecialchars($tarea['titulo']) ?>
                </span>
                <a href="editar.php?id=<?= $tarea['id'] ?>">Editar</a>
                <a href="eliminar.php?id=<?= $tarea['id'] ?>" 
                onclick="return confirm('¿Estás seguro de que quieres borrar esta tarea?');"
                style="color: red;">Eliminar
                </a>
            </li>
        <?php endforeach; ?>


    </ul>
 <script>
    // Seleccionamos todos los checkboxes
    const checkboxes = document.querySelectorAll('.check-tarea');

    checkboxes.forEach(box => {
        box.addEventListener('change', function() {
            const id = this.dataset.id; // Obtenemos el ID de la tarea
            const texto = document.getElementById('texto-' + id);

            // LLAMADA AJAX (FETCH)
            fetch('api/toggle_tarea.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Si todo salió bien en el servidor, actualizamos la vista visualmente
                    if (this.checked) {
                        texto.style.textDecoration = 'line-through';
                    } else {
                        texto.style.textDecoration = 'none';
                    }
                    console.log('Tarea actualizada correctamente');
                } else {
                    alert('Hubo un error al actualizar');
                    // Revertimos el checkbox si falló
                    this.checked = !this.checked;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>   
    <?php if (!$mensaje): ?>
        <p style="color: blue;"><strong><?= $mensaje ?></strong></p>
    <?php endif; ?> 

    <form action="index.php" method="POST">
        <label>Título:</label><br>
        <input type="text" name="titulo" required><br><br>
        
        <label>Descripción:</label><br>
        <textarea name="descripcion"></textarea><br><br>
        
        <button type="submit">Guardar Tarea</button>
        <a href="logout.php">Cerrar Sesión</a>
    </form>
</body>
</html>