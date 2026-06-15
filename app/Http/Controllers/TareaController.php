<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;

class TareaController extends Controller
{
    //
    public function index() {
        $userId = session('usuario_id');
        $tareas = Tarea::where('user_id', $userId)->get();
        return response()->json($tareas);
    }
    public function show($id){
        $userId = session('usuario_id');
        $tarea = Tarea::where('id', $id)->where('user_id', $userId)->first();
        if(!$tarea) {
            return response()->json(['error' => 'Tarea no encontrada'], 404); 
        }
        return response()->json($tarea);
    }
    public function store(Request $request) {
        // Usar sistema integrado de validación. $request->validate()
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'nullable|string|max:50']);
        $userId = session('usuario_id');
        // Si no esta vacío, compruebo en la BBDD. TODA la tabla, no solo del user
        $existe = Tarea::where('titulo', $validated['titulo'])->where('user_id', $userId)->exists();
        if ($existe) {
            return response()->json(['error' => 'Esta tarea ya ha sido asignada a este usuario'], 409);
            }                                                                   // only 'descripcion' can be nullable 
        $nuevo = Tarea::create(['titulo' => $validated['titulo'], 'descripcion' => $validated['descripcion'] ?? NULL, 'categoria' => $validated['categoria'] ?? NULL, 'user_id' => $userId]);
        return response()->json($nuevo, 201);
    }

    public function update($id, Request $request) {
        $userId = session('usuario_id');
        $tarea = Tarea::where('id', $id)->where('user_id', $userId)->first();
        if (!$tarea) {
            return response()->json(['error' => 'Tarea no encontrada'], 404);
        }
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'nullable|string|max:50']); // busca el dato enviado en la petición HTTP
        $tarea->update(['titulo' => $validated['titulo'], 'descripcion'  => $validated['descripcion'] ?? null, 'categoria' => $validated['categoria'] ?? NULL, 'completada' => $request->input('completada')]);
        // ->update(): devuelve T/F (boolean), no la tarea
        return response()->json($tarea, 200);
    } // Usuario solo puede actualizar/eliminar sus tareas
    public function destroy($id) {
        $userId = session('usuario_id');
        $tarea = Tarea::where('id', $id)->where('user_id', $userId)->first();
        if (!$tarea) {
            return response()->json(['error' => 'Tarea no encontrada'], 404);
        }
        $tarea->delete();
        return response()->json(['message' => 'Tarea eliminada correctamente']);
    }
}
