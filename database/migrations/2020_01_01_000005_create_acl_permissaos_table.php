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
        Schema::create('acl_permissaos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entidade_id')->unsigned();

            $table->string('rota', 30)->unique();
            $table->text('descricao')->nullable();
            $table->enum('ativo', ['SIM', 'NÃO'])->default('SIM');
            $table->timestamps();

            $table->foreign('entidade_id', 'permissaos_pertence_entidade_fkey')->references('id')->on('acl_entidades')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acl_permissaos');
    }
};
