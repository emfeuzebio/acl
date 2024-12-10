<?php

namespace Database\Seeders;

use App\Models\Entidade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ACL_EntidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('acl_entidades')->insert([
            [
                'tabela' => 'acl_entidades',
                'model' => 'Entidade',
                'descricao' => 'Armazena lista de Entidades',
                'ativo' => 'SIM',
            ],[
                'tabela' => 'acl_perils',
                'model' => 'Perfil',
                'descricao' => 'Armazena lista de Perfis de Acesso',
                'ativo' => 'SIM',
            ],
            [
                'tabela' => 'acl_permissaos',
                'model' => 'Permissao',
                'descricao' => 'Armazena lista de Permissões na Entidade',
                'ativo' => 'SIM',
            ],
        ]);        

        // este funciona já tendo o Model e para inserir uma única linha
        // Entidade::create([
        //     'tabela' => 'acl_perfil_user',
        //     'model' => 'Perfil',
        //     'descricao' => 'Armazena relação Many to Many: User pode ter muitos Perfis',
        //     'ativo' => 'SIM',
        // ]);        
        
    }
}