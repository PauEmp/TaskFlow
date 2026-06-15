<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Importar modelo User
// Crear controlador ApiController que hereda de la clase base Controller
class ApiController extends Controller
{
    // Método se ejecutará cuando una ruta apunte a ApiController@index
    public function index() {
// Obtener todos los registros de la tabla "users" usando el modelo User
        // $usuarios = User::all(); No queremos todos los datos (protección)
        $usuarios = User::select('id', 'name')->get();
// Convierte los usuarios a formato JSON y los devuelve como respuesta de la API
        return response()->json($usuarios);
    } // e.g: [{ "id": 1, "name": "Juan" }, { "id": 2, "name": "Ana"}]
    // Creamos una ruta para q el servidor busque por 'id' y no 'name'
    public function show($id) {             // busca en la PK
        $usuario = User::select('id', 'name')->find($id); 
        if(!$usuario) { // "usuario" con ID no encontrado
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
        return response()->json($usuario);
    }
    public function store(Request $request) {
        $usuario = $request->input('usuario');
        $email = $request->input('email');
        $password = $request->input('password');
        if(empty($usuario)|| empty($email) || empty($password)) {
            return response()->json(['error' => 'Todos los campos son obligatorios'], 400);
        }
        $existe = User::where('name',$usuario)->orWhere('email', $email)->exists();
        if ($existe) {
            return response()->json(['error' => 'Usuario o email ya existen'], 409);
        }
        $nuevo = User::create(['name' => $usuario, 'email' => $email, 'password' => $password]);
        return response()->json($nuevo, 201);
    }
}
