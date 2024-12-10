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
        Schema::create('acl_perfil_user', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('user_id')->unsigned();
            $table->integer('perfil_id')->unsigned();
            $table->unique(["user_id", "perfil_id"], 'perfil_users_ukey');

            $table->foreign('perfil_id','perfil_user_users_fkey')->references('id')->on('acl_perfils')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('user_id',  'perfil_perfil_users_fkey')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acl_perfil_user');
    }
};
