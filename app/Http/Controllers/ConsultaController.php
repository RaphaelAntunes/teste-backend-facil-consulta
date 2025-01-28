<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\Consulta;

use Illuminate\Http\Request;
use Carbon\Carbon;

class ConsultaController extends Controller
{
   

    public function store(Request $request)
{
    // Validação dos dados
    $validatedData = $request->validate([
        'medico_id' => 'required|exists:medicos,id', // Verifica se o médico existe
        'paciente_id' => 'required|exists:pacientes,id', // Verifica se o paciente existe
        'data' => 'required|date_format:Y-m-d H:i:s', // Valida formato de data e se é futura
    ]);

    // Verificar se o horário já está marcado para o médico
    $consultaExistente = Consulta::where('medico_id', $validatedData['medico_id'])
        ->where('data', $validatedData['data'])
        ->first();

    if ($consultaExistente) {
        return response()->json(['message' => 'Este horário já está ocupado para o médico.'], 422);
    }

    $consulta = Consulta::create([
        'medico_id' => $validatedData['medico_id'],
        'paciente_id' => $validatedData['paciente_id'],
        'data' => $validatedData['data'],
    ]);

    return response()->json($consulta, 201);
}

public function listarPacientesPorMedico($id_medico, Request $request)
    {
        $dataAtual = Carbon::now(); 
        // Verifica se medico existe
        $medico = Medico::find($id_medico);
        if (!$medico) {
            return response()->json(['message' => 'Médico não encontrado'], 404);
        }

        // Parâmetros
        $apenasAgendadas = filter_var($request->input('apenas-agendadas', false), FILTER_VALIDATE_BOOLEAN);
        $nomePaciente = $request->input('nome');

        // Consulta
        $query = Consulta::with('paciente')
            ->where('medico_id', $id_medico)
            ->orderBy('data', 'asc');

        // Filtrar apenas consultas não realizadas (com base na data)
        if ($apenasAgendadas) {
            $query->where('data', '>', $dataAtual); 
        }

        // Buscar por parte do nome do paciente
        if ($nomePaciente) {
            $query->whereHas('paciente', function ($q) use ($nomePaciente) {
                $q->where('nome', 'like', '%' . $nomePaciente . '%');
            });
        }

        $consultas = $query->get();

        $pacientes = $consultas->map(function ($consulta) {
            return [
                'id' => $consulta->paciente->id,
                'nome' => $consulta->paciente->nome,
                'cpf' => $consulta->paciente->cpf,
                'celular' => $consulta->paciente->celular,
                'created_at' => $consulta->paciente->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $consulta->paciente->updated_at->format('Y-m-d H:i:s'),
                'deleted_at' => $consulta->paciente->deleted_at ? $consulta->paciente->deleted_at->format('Y-m-d H:i:s') : null,
                'consulta' => [
                    'id' => $consulta->id,
                    'data' => $consulta->data,
                    'created_at' => $consulta->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $consulta->updated_at->format('Y-m-d H:i:s'),
                    'deleted_at' => $consulta->deleted_at ? $consulta->deleted_at->format('Y-m-d H:i:s') : null,
                ],
            ];
        });

        return response()->json($pacientes, 200);
    }


}
