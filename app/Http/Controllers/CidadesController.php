<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use App\Models\Medico;

use Illuminate\Http\Request;

class CidadesController extends Controller
{
   
    public function index(Request $request)
    {
        $nome = $request->query('nome');

        // Consulta Cidades
        $query = Cidade::query();

        if ($nome) {
            $query->where('nome', 'LIKE', '%' . $nome . '%');
        }

        // Define campos resposta
        $cidade = $query->select(['id', 'nome', 'estado'])
            ->orderBy('nome', 'asc')
            ->get();

        return response()->json($cidade);
    }


    public function listByCity(Request $request, $id_cidade)
    {
        $nome = $request->query('nome');

        // Verifica se existe a cidade, caso não encerra a consulta
        if (!Cidade::find($id_cidade)) {
            return response()->json(['error' => 'Cidade não encontrada'], 404);
        }

        $query = Medico::where('cidade_id', $id_cidade);

        if ($nome) {
            // Remove prefixos "Dr" e "Dra" do nome buscado
            $nomeUpper = strtoupper($nome);

            if (substr($nomeUpper, 0, 3) === 'DRA') {
                $nome = substr($nome, 3);
            } elseif (substr($nomeUpper, 0, 2) === 'DR') {
                $nome = substr($nome, 2);
            }

            // Adiciona o filtro pelo nome
            $query->where('nome', 'LIKE', '%' . $nome . '%');
        }

        // Define campos resposta
        $medicos = $query->select(['id', 'nome', 'especialidade', 'cidade_id'])
            ->orderBy('nome', 'asc')
            ->get();


        return response()->json($medicos);
    }


}
