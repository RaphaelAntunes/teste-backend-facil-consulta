<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use Illuminate\Http\Request;

class MedicoController extends Controller
{
    public function index(Request $request)
    {
        $nome = $request->query('nome');

        // Consulta médicos
        $query = Medico::query();

        if ($nome) {
            // Transforma o nome em uppercase para padronizar a verificação
            $nomeUpper = strtoupper($nome);

            // Remove padroes Dr/Dra
            if (substr($nomeUpper, 0, 3) === 'DRA') {
                $nome = substr($nome, 3);
            } elseif (substr($nomeUpper, 0, 2) === 'DR') {
                $nome = substr($nome, 2);
            }


        }

        // Define campos resposta
        $query = Medico::select(['id', 'nome', 'especialidade', 'cidade_id']);

        if ($nome) {
            $query->where('nome', 'LIKE', '%' . $nome . '%');
        }


        // Retorna os médicos no formato desejado
        return response()->json($query->get());


    }


    public function store(Request $request)
    {

        // Validação dos dados
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'especialidade' => 'required|string|max:255',
            'cidade_id' => 'required|exists:cidades,id',
        ]);

        // Criar o médico no banco de dados
        $medico = Medico::create($validatedData);

        // Retornar a resposta
        return response()->json($medico, 201);

    }
}
