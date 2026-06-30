<?php
// Para comprobar que la API de tareas funciona (crear, validar y listar tareas)
namespace Tests\Feature; // este test pertence a los test de funcionalidad (feature Tests)
use Tests\TestCase; //  base de los test en Laravel
use App\Models\User; // para poder usar modelo User
use App\Models\Tarea; // "                  " Tareas
use Illuminate\Foundation\Testing\RefreshDatabase;

// Define el grupo de pruebas relacionadas con tareas
class TareaTest extends TestCase {
    use RefreshDatabase; // Resetea la BD entre cada test para evitar datos viejos
    // Función auxiliar de crear usuario y "login"
    private function crearUsuarioYLogin(): User { // Function that give User (object) back
        $usuario = User::create([           // : indicates the return type of a function
            'name' => 'TestUser',
            'email' => 'test@test.com',
            'password' => 'password123'
        ]); // Se crea un usario en la BD (En producción se debe encriptar)
        // Simula el login guardando la sesión. Usuario "autenticado"
        session(['usuario' => $usuario->name, 'usuario_id' => $usuario->id]);
        return $usuario;
    }
    // Comprueba que se pueda crear una tare válida
    public function test_crear_tarea_correctamente(): void {
        $usuario = $this->crearUsuarioYLogin();
        // Simula sesión activa en la petición HTTP
        $response = $this->withSession([
            'usuario' => $usuario->name,
            'usuario_id' => $usuario->id
        ])->postJson('/api/tareas', [
            'titulo' => 'Tarea de prueba',
            'descripcion' => 'Descripción de prueba',
            'categoria' => 'Ctegoria de prueba'
        ]); // postJosn: Hace petición POST a la API enviando JSON (título, descripción)
        $response->assertStatus(201)->assertJsonFragment(['titulo' => 'Tarea de prueba']);
    } // Verifica respuesta sea 201 (Created), y que la respuesta JSON contiene ese título
    // Verificar validaciones
    public function test_no_crear_tarea_sin_titulo(): void {
        $usuario = $this->crearUsuarioYLogin();
        $response = $this->withSession([
            'usuario' => $usuario->name,
            'usuario_id' => $usuario->id
        ])->postJson('/api/tareas', ['descripcion' => 'Sin titulo']);
        $response->assertStatus(422); // Validación fallida, xk se envía la validación "Sin título"
    }
    // Test that the logged-in user can retrieve their own tasks
    public function test_listar_tareas_del_usuario(): void {
        $usuario = $this->crearUsuarioYLogin(); // Create a test user & log them in
        Tarea::create(['titulo' => 'Tarea 1', 'user_id' => $usuario->id]); // Creates 2 tasks
        Tarea::create(['titulo' => 'Tarea 2', 'user_id' => $usuario->id]); // belonging to that user
        $response = $this->withSession([ // Simulate the user's session (before making the request)
            // Creates a fake (array of) session (data)
            'usuario' => $usuario->name,
            'usuario_id' => $usuario->id
        ])->getJson('/api/tareas'); // & make a GET request to the API
        $response->assertStatus(200)->assertJsonCount(2);
    } // Verify the request was successful & exactly 2 taks were returned
}