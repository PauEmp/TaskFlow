<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\TareaController;

// GET: mostrar pág. | POST: enviar datos
// middleware() para las rutas q requieren q el user esté logueado (autenticado)
Route::get('/', [AuthController::class, 'index']);
Route::get('/login', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']); //necesita procesa formulario
Route::get('/registro', [AuthController::class, 'mostrarRegistro']);
Route::post('/registro', [AuthController::class, 'registro']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/dashboard', [AuthController::class, 'dashboard'])->middleware(AuthMiddleware::class);

Route::get('/api/usuarios', [ApiController::class, 'index']); // Formato JSON Api, no Auth
Route::get('/api/usuarios/{id}', [ApiController::class, 'show']);
Route::post('/api/usuarios', [ApiController::class, 'store']);

Route::get('/api/tareas', [TareaController::class, 'index'])->middleware(AuthMiddleware::class);
Route::post('/api/tareas', [TareaController::class, 'store'])->middleware(AuthMiddleware::class);
Route::put('/api/tareas/{id}', [TareaController::class, 'update'])->middleware(AuthMiddleware::class);// Update
Route::delete('/api/tareas/{id}', [TareaController::class, 'destroy'])->middleware(AuthMiddleware::class);// Delete