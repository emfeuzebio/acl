<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ACL_EntidadePerfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('acl_entidade_perfil')->insert([
            [
                'perfil_id' => '1',
                'entidade_id' => '1',
            ],
            [
                'perfil_id' => '1',
                'entidade_id' => '2',
            ],
            [
                'perfil_id' => '1',
                'entidade_id' => '3',
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