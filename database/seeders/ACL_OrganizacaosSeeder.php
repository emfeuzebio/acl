<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ACL_OrganizacaosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('acl+organizacaos')->insert([
            [
                'nome' => 'Organização A',
                'tipo' => 'ONG',
                'descricao' => 'Descrição da Organização A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Organização B',
                'tipo' => 'Empresa',
                'descricao' => 'Descrição da Organização B',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Adicione mais registros conforme necessário
        ]);
    }
}
