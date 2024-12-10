<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ACL_PerfilsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('acl_perfils')->insert([
            [
                'nome' => 'Administrador',
                'descricao' => 'Administrador do Sistema',
                'ativo' => 'SIM',
            ],
            [
                'nome' => 'Gerente',
                'descricao' => 'Gerente do Sistema',
                'ativo' => 'SIM',
            ],
            [
                'nome' => 'Usuário',
                'descricao' => 'Usuário do Sistema',
                'ativo' => 'SIM',
            ],
        ]);
    }
}