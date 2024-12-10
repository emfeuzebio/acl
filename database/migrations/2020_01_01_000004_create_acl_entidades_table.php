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
        Schema::create('acl_entidades', function (Blueprint $table) {
            $table->increments('id');

            $table->string('tabela', 30)->nullable();      
            $table->string('model', 30)->nullable();
            $table->text('descricao')->nullable();
            $table->enum('ativo', ['SIM', 'NÃO'])->default('SIM');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acl_entidades');
    }
};
