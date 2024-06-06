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
        Schema::create('config', function (Blueprint $table) {
            $table->id();
            $table->string('login');
            $table->string('password')->hash('sha512');
            $table->unsignedBigInteger('id_role');
            $table->foreign('id_role')->references('id')->on('role');
            $table->timestamp('create_time')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
