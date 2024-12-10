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
        Schema::create('acl_log', function (Blueprint $table) {
            $table->increments('id');                       

            $table->integer('organizacao_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('endereco_ip', 50)->nullable();
            $table->text('descricao')->nullable();
            $table->timestamp('created_at')->useCurrent();      
            $table->timestamp('updated_at')->useCurrent();      

            $table->foreign('organizacao_id')->references('id')->on('organizacaos')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acl_log');
    }
};
