<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cidade;

class CidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cidades = [
            ['id' => 1, 'nome' => 'Pelotas', 'estado' => 'RS'],
            ['id' => 2, 'nome' => 'São Paulo', 'estado' => 'SP'],
            ['id' => 3, 'nome' => 'Curitiba', 'estado' => 'PR'],
            ['id' => 4, 'nome' => 'Natal', 'estado' => 'RN'],
            ['id' => 5, 'nome' => 'João Pessoa', 'estado' => 'PB'],
        ];

        foreach ($cidades as $cidade) {
            Cidade::updateOrCreate(['id' => $cidade['id']], $cidade);
        }
    }
}
