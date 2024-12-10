<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ACL_PerfilUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('acl_perfil_user')->insert([
            // User 1 - SuperAdmin - Todos Perfis
            [
                'user_id' => '1',
                'perfil_id' => '1',
            ],
            [
                'user_id' => '1',
                'perfil_id' => '2',
            ],
            [
                'user_id' => '1',
                'perfil_id' => '3',
            ],
            [
                'user_id' => '2',
                'perfil_id' => '2',
            ],
            [
                'user_id' => '3',
                'perfil_id' => '3',
            ],
            [
                'user_id' => '4',
                'perfil_id' => '3',
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