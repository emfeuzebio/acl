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
        Schema::create('acl_entidade_perfil', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('perfil_id')->unsigned();
            $table->integer('entidade_id')->unsigned();
            $table->unique(["perfil_id", "entidade_id"], 'entidade_perfil_ukey');

            $table->foreign('perfil_id',   'entidade_perfil_perfils_fkey')->references('id')->on('acl_perfils')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('entidade_id', 'entidade_perfil_entidades_fkey')->references('id')->on('acl_entidades')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acl_entidade_perfil');
    }
};
