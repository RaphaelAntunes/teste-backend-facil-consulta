<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:20',
            'celular' => 'required|string|max:20',
        ]);

        $paciente = Paciente::create($validatedData);

        return response()->json($paciente, 201);

    }

    public function atualizarPaciente($id_paciente, Request $request)
    {
        // Verificar se o paciente existe
        $paciente = Paciente::find($id_paciente);
        if (!$paciente) {
            return response()->json(['message' => 'Paciente não encontrado'], 404);
        }

        // Permite que atualize apenas nome e celualr
        $validatedData = $request->validate([
            'nome' => 'sometimes|string|max:255',
            'celular' => 'sometimes|string|max:20',
        ]);

        $paciente->update($validatedData);

        return response()->json($paciente, 200);
    }

}
