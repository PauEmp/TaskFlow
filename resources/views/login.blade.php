<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <!-- AController: si se crea un msj de 'error' lo muuestra -->
    @if(session('error'))
        <!-- imprime en Blade sin riesgo de XSS:
            imprime el valor de $nombre en HTML, pero 1ero lo escapa
            Convierte car. peligrosos para q no se ejecuten como código -->
        <p>{{ session('error')}}</p>
    @endif
    <!-- cuando se pulsa el botón se envía los datos a /login usando POST-->
    <form action="/login" method="POST">
        <!-- Cross-Site Request Forgery: si intenta hacer un POST desde una pág. maliciosa
        crsf genera un toekn special y el servidor laravel comprueba que conicida para aceptar la petición -->
        @csrf
        <input type="text" name="usuario" placeholder="Usuario">
        <input type="password" name="contrasena" placeholder="Contraseña">
        <button tyoe="sumbit">ENTRAR</button>
    </form>
</body>
</html>