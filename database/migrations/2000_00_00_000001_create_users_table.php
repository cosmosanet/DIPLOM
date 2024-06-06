<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->unsignedBigInteger('id_role')->nullable();
            $table->foreign('id_role')->references('id')->on('role');
            $table->string('status')->nullable();
            $table->timestamp('create_time')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
       
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
