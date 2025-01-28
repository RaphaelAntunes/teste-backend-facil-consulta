<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// LOGIN
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::get('user', [AuthController::class, 'user'])->middleware('auth:api');


// MEDICOS
Route::post('medicos', [MedicoController::class, 'store'])->middleware('auth:api');;
Route::get('medicos', [MedicoController::class, 'index']);


//CIDADES
Route::get('/cidades/{id_cidade}/medicos', [MedicoController::class, 'listByCity']);

//Paciente
Route::post('pacientes', [PacienteController::class, 'store'])->middleware('auth:api');;
Route::post('/pacientes/{id_paciente}', [PacienteController::class, 'atualizarPaciente'])->middleware('auth:api');
