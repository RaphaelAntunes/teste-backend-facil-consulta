<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\CidadesController;

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
Route::get('/cidades/{id_cidade}/medicos', [CidadesController::class, 'listByCity']);
Route::get('cidades', [CidadesController::class, 'index']);

//PACIENTES
Route::middleware(['auth:api'])->group(function () {
    Route::post('pacientes', [PacienteController::class, 'store']);
    Route::post('/pacientes/{id_paciente}', [PacienteController::class, 'atualizarPaciente']);
});

//CONSULTA
Route::post('/medicos/consulta', [ConsultaController::class, 'store'])->middleware('auth');
Route::get('/medicos/{id_medico}/pacientes', [ConsultaController::class, 'listarPacientesPorMedico']);
