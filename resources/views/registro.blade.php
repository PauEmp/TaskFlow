<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>
<body>
    @if(session('error'))
        <p>{{ session('error')}}</p>
    @endif
    <form action="/registro" method="POST">
        @csrf
        <input type="text" name="usuario" placeholder="Registra el nombre">
        <input type="text" name="email" placeholder="Introduce email">
        <input type="password" name="contrasena" placeholder="Contrseña">
        <button type="submit">REGISTRAR</button>
    </form>
</body>
</html>