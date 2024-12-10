<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ACL_PermissaosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('acl_permissaos')->insert([
            // Entidade Entidade
            [
                'entidade_id' => '1',
                'rota' => 'entidade.index',
                'descricao' => 'Listar Entidades',
                'ativo' => 'SIM',
            ],
            [
                'entidade_id' => '1',
                'rota' => 'entidade.show',
                'descricao' => 'Mostrar Entidade',
                'ativo' => 'SIM',
            ],
            [
                'entidade_id' => '1',
                'rota' => 'entidade.edit',
                'descricao' => 'Editar Entidade',
                'ativo' => 'SIM',
            ],
            [
                'entidade_id' => '1',
                'rota' => 'entidade.destroy',
                'descricao' => 'Excluir Entidade',
                'ativo' => 'SIM',
            ],
            [
                'entidade_id' => '1',
                'rota' => 'entidade.store',
                'descricao' => 'Salvar Entidade',
                'ativo' => 'SIM',
            ],
            [
                'entidade_id' => '1',
                'rota' => 'entidade.list',
                'descricao' => 'Listar as Entidades',
                'ativo' => 'SIM',
            ],
            // Entidade Perfil
            [
                'entidade_id' => '2',
                'rota' => 'perfil.index',
                'descricao' => 'Listar Perfil',
                'ativo' => 'SIM',
            ],
            [
                'entidade_id' => '2',
                'rota' => 'perfil.show',
                'descricao' => 'Mostrar Perfil',
                'ativo' => 'SIM',
            ],
            [
                'entidade_id' => '2',
                'rota' => 'perfil.edit',
                'descricao' => 'Editar Perfil',
                'ativo' => 'SIM',
            ],
            [
                'entidade_id' => '2',
                'rota' => 'perfil.destroy',
                'descricao' => 'Excluir Perfil',
                'ativo' => 'SIM',
            ],
            [
                'entidade_id' => '2',
                'rota' => 'perfil.store',
                'descricao' => 'Salvar Perfil',
                'ativo' => 'SIM',
            ],          
            // Entidade Permissao
            [
                'entidade_id' => '3',
                'rota' => 'permissao.index',
                'descricao' => 'Listar Permissão',
                'ativo' => 'SIM',
            ],
            [
                'entidade_id' => '3',
                'rota' => 'permissao.show',
                'descricao' => 'Mostrar Permissão',
                'ativo' => 'SIM',
            ],
            [
                'entidade_id' => '3',
                'rota' => 'permissao.edit',
                'descricao' => 'Editar Permissão',
                'ativo' => 'SIM',
            ],
            [
                'entidade_id' => '3',
                'rota' => 'permissao.destroy',
                'descricao' => 'Excluir Permissão',
                'ativo' => 'SIM',
            ],
            [
                'entidade_id' => '3',
                'rota' => 'permissao.store',
                'descricao' => 'Salvar Permissão',
                'ativo' => 'SIM',
            ],                  
        ]);        
    }
}