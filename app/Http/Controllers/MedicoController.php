<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\Cidade;

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

            // Remove prefixos "Dr" e "Dra" do nome buscado
            if (substr($nomeUpper, 0, 3) === 'DRA') {
                $nome = substr($nome, 3);
            } elseif (substr($nomeUpper, 0, 2) === 'DR') {
                $nome = substr($nome, 2);
            }

            $query->where('nome', 'LIKE', '%' . $nome . '%');
        }

        // Define campos resposta
        $medicos = $query->select(['id', 'nome', 'especialidade', 'cidade_id'])
            ->orderBy('nome', 'asc')
            ->get();

        return response()->json($medicos);
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
