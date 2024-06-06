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
        Schema::create('file', function (Blueprint $table) {
            $table->string('md5')->unique();
            $table->string('sha1')->unique();
            $table->string('sha512')->unique();
            $table->primary(['md5', 'sha1', 'sha512']);
            $table->string('file_name');
            $table->unsignedBigInteger('id_user');
            $table->string('status')->default('отправлен');
            $table->foreign('id_user')->references('id')->on('users');
            $table->timestamp('create_time')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
