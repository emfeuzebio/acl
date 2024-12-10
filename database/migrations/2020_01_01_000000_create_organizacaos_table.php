<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Dropar a tabela, se existir, antes de criar uma nova
        // Schema::dropIfExists('acl_organizacaos');
        
        if (!Schema::hasTable('acl_organizacaos')) {
            Schema::create('acl_organizacaos', function (Blueprint $table) {
                $table->increments('id');       
                $table->string('sigla', 30);
                $table->string('nome', 128);
                $table->text('descricao')->nullable();  
                $table->enum('ativo', ['SIM', 'NÃO'])->default('SIM');
                $table->timestamps();                   // Campos created_at e updated_at
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acl_organizacaos');
    }
};
