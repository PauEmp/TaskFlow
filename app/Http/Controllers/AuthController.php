<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB; // Importa la clase (DB)
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Importa modelo user. Representa la tabla users.
                    // No hace crear conn. Con Laravel es automático
// creates a controller, named AController 
class AuthController extends Controller {
    public function index() {
        // User in /, lavarvel runs index() -> login.blade.php
        return view('login');
    }
    // Process the login form & Request  $request contains the data sent by the user
    public function login(Request $request) {
        // to read <input name="usuario/contrasena"> 
        $usuario = $request->input('usuario');
        $contrasena = $request->input('contrasena');
        // To go back to the prev page and show and error mssg
        // with('error', ...): crea un mensaje de error 
        if (empty($usuario) || empty($contrasena)) {
            return back()->with('error', 'Rellena todos los campos');
        }
        // Conn. BD, en la tabla 'users' busca el 1er registro donde name  = $usuario
        $fila = User::where('name', $usuario)->first();
        if ($fila && Hash::check($contrasena, $fila->password)) {
            session(['usuario' => $fila->name]);
            session(['usuario_id' => $fila->id]);
            return redirect('/dashboard');
        }
        return back()->with('error', 'Credenciales incorrectas');
    }
    // Show the dashboard: loads resources/views/dashboard.blade.php
    public function dashboard() {
        // has: verifica que la clave existe en la sesión
        if(!session()->has('usuario')) {
            return redirect('login');
        } // !: la sesión no existe
        return view('dashboard');
    }
    // 
    public function logout() {
        // delete all session data (user's login info, temporary data, etc.)
        session()->flush();
        // send user back to the home page
        return redirect('/');
    }
    //
    public function mostrarRegistro() {
        return view('registro');
    }
    //
    public function registro(Request $request) {
        $usuario = $request->input('usuario');
        $email = $request->input('email');
        $contrasena = $request->input('contrasena');
        if (empty($usuario) || empty($email) || empty($contrasena)) {
            return back()->with('error', 'Rellena todos los campos');
        }
        // Check if user already exits in DB
        $fila = User::where('name', $usuario)->first();
        if($fila) {
            return back()->with('error', 'Este usuario ya existe');
        }
        // Create User. Si no entra en ningún IF
        User::create(['name' => $usuario, 'email' => $email, 'password' => $contrasena]);
        return redirect('/');
    }
}