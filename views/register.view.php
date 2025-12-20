<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <script src="https://cdn.twind.style" crossorigin></script>
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-6 text-center">
                <h1 class="text-3xl font-bold">Crear Cuenta</h1>
                <p class="mt-2 opacity-90">Únete a nuestra comunidad</p>
            </div>
            <form method="POST" class="p-6 md:p-8">
                <div class="mb-6">
                    <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required 
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                            placeholder="tucorreo@ejemplo.com"
                        >
                    </div>
                </div>
                <div class="mb-8">
                    <label for="contrasena" class="block text-gray-700 font-medium mb-2">Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input 
                            type="password" 
                            id="contrasena" 
                            name="contrasena" 
                            required 
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                            placeholder="Escribe tu contraseña"
                        >
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Mínimo 8 caracteres con letras y números</p>
                </div>
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 transform hover:-translate-y-0.5"
                >
                    Registrarse
                </button>
                <div class="mt-6 text-center">
                    <a 
                        href="login.php" 
                        class="text-blue-600 hover:text-blue-800 font-medium transition duration-200 inline-flex items-center"
                    >
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        ¿Ya tienes cuenta? Inicia sesión
                    </a>
                </div>
            </form>
            
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 text-center">
                    Al registrarte, aceptas nuestros 
                    <a href="#" class="text-blue-600 hover:underline">Términos de Servicio</a> 
                    y 
                    <a href="#" class="text-blue-600 hover:underline">Política de Privacidad</a>.
                </p>
            </div>
        </div>
        <div class="mt-8 text-center hidden md:block">
            <p class="text-gray-600">
                <span class="font-medium">¿Problemas para registrarte?</span> 
                <a href="#" class="text-blue-600 hover:underline ml-1">Contacta con soporte</a>
            </p>
        </div>
    </div>
    
    <div class="mt-10 text-center text-gray-500 text-sm max-w-md">
        <p>Este formulario utiliza <span class="font-medium text-blue-600">Twind</span> para un diseño completamente responsive.</p>
        <p class="mt-1">Se adapta en automatico.</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const passwordInput = document.getElementById('contrasena');
            
            form.addEventListener('submit', function(e) {
                const password = passwordInput.value;
                
                if (password.length < 8) {
                    alert('La contraseña debe tener al menos 8 caracteres.');
                    e.preventDefault();
                    return;
                }
                
                e.preventDefault();
                alert('Formulario enviado correctamente (simulación). En una aplicación real, esto enviaría los datos al servidor.');
                
                form.reset();
            });
            
            const submitButton = document.querySelector('button[type="submit"]');
            submitButton.addEventListener('mouseenter', function() {
                this.classList.add('shadow-lg');
            });
            
            submitButton.addEventListener('mouseleave', function() {
                this.classList.remove('shadow-lg');
            });
        });
    </script>
</body>
</html>
