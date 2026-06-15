<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!--Para q JScript pueda leer el token CSRF de Laravel y enviarlo en
    peticiones POST, PUT, PATCH o DELETE --> 
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- csrf_token(): Laravel genera un token único para la sesión del usuario
    ex: content="abc123xyz..." -->
</head>
<body>
    <h1>Hola, {{ session('usuario')}} </h1>
    <!-- al hacer el click, ejecuta la función cargarUser() -->
    <button onclick="cargarUsuarios()">Cargar usuarios</button>
<br><br>
    <input type="number" id="id_user" placeholder=" Introduce ID del usuario">
    <button onclick="buscarPorID()">BUSCAR</button>
    <ul id="lista"></ul> <!-- lista q muestrará los usuarios -->

    <input type="text" id="nuevo_usuario" placeholder="Nombre">
    <input type="text" id="nuevo_email" placeholder="Email">
    <input type="password" id="nuevo_password" placeholder="** Contraseña **">
    <button onclick="crearUsuario()">Crear Usuario</button>
<br><br>
    <input type="text" id="nueva_tarea_titulo" placeholder="Titulo de la tarea">
    <input type="text" id="nueva_tarea_desc" placeholder="Descripción">
    <button onclick="crearTarea()">Crear Tarea</button>
    <ul id="lista_tareas"></ul>
<br>
    <input type="number" id="tarea_id" placeholder="ID de la tarea">
    <button onclick="cargarTarea()">Cargar Tarea</button>
    <ul id="cargar_tareas"></ul>
    <button onclick="completarTarea()">Marcar como completada</button>
    <button onclick="borrarTarea()">Borrar tarea</button>
<br><br>
    <a href="/logout">Cerrar Sesión</a>
    <script> // función se ejecuta al cargar el botón
        function cargarUsuarios() {
            // hace una petición HTTP GET al backend Laravel a /api/user
            fetch('/api/usuarios')
            // Convertir la respuesta del servidor en objeto JavaScript
            .then(response => response.json())
            // datos: es el array de users (JSON) q devuelve Laravel
            .then(datos => {
                // selecciona <ul>
                const lista = document.getElementById('lista');
                // y borra contenido anterior. Para evitar duplicados en doble clic
                lista.innerHTML = '';
                // recorre cada usuario del array
                datos.forEach(usuario => {
                    // ${}: permite insertar variables dentro de strings
                    lista.innerHTML += `<li>${usuario.id} - ${usuario.name}</li>`;
                });
            });
        }
        function buscarPorID() {
            const id = document.getElementById('id_user').value;
            fetch(`/api/usuarios/${id}`)
            .then(response => response.json())
            .then(usuario => {
                const lista_user = document.getElementById('lista');
                lista_user.innerHTML = `<li>${usuario.id} - ${usuario.name}</li>`;
            });
        }
        // envía los datos del formulario a la API para crear un usuario
        function crearUsuario() {
            // Realiza una petición HTTP POST a la ruta /api/usuarios. fetc(): envía petición a la API
            fetch(`/api/usuarios`, {
                // Indica que se va a crear un usuario
                method: 'POST',
                // define el tipo de datos y el token CSRF (que envía Laravel)
                headers: {
                    // Informa al servidor que enviamos datos JSON
                    'Content-Type': 'application/json',
                    // Token CSRF de Laravel para proteger contra ataques CSRF
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                // Convierte el objeto JavaScript a formato JSON
                body: JSON.stringify({ // Obtiene el valor de cada campo
                    usuario: document.getElementById('nuevo_usuario').value,
                    email: document.getElementById('nuevo_email').value,
                    password: document.getElementById('nuevo_password').value
                })
            })
            // Convierte la respuesta JSON del servidor a un objeto JavaScript
            .then(response => response.json())
            // Muestra en consola la respuesta recibida
            .then(datos => console.log(datos));
        }
        function crearTarea() {
            fetch(`/api/tareas`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    titulo: document.getElementById('nueva_tarea_titulo').value,
                    descripcion: document.getElementById('nueva_tarea_desc').value
                })
            })
            .then(response => response.json())
            .then(datos => {
                console.log(datos);
                alert('Tarea creada con éxito!');
            });
        }
        function cargarTarea() {
            fetch('/api/tareas')
            .then(response => response.json())
            .then(datos => {
                const lista = document.getElementById('cargar_tareas');
                lista.innerHTML = ''; // NARANJA: variable para cada elemento del array
                if (datos.length === 0) {
                    lista.innerHTML = '<li>Este usuario no tiene tareas asignadas</li>';
                    return;
                }
                datos.forEach(tarea => {
                    lista.innerHTML += `<li>${tarea.id} - ${tarea.titulo}</li>`;
                });
            })
            .then(datos => console.log(datos));
        }
        function completarTarea() {
            const id = document.getElementById('tarea_id').value; // xk es id_user¿?
            fetch(`/api/tareas/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    titulo: 'Actualizado',
                    descripcion: 'Actualizado',
                    completada: true
                })
            })
            .then(response => response.json())
            .then(datos => console.log(datos));
        }
        function borrarTarea() {
            const id = document.getElementById('tarea_id').value;
            fetch(`/api/tareas/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(datos => console.log(datos));
        }
    </script>
</body>
</html>