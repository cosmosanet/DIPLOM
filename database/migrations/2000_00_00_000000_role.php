<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // public function up(): void
    // {
    //     Schema::create('role', function (Blueprint $table) {
    //         $table->id();
    //         $table->string('name_roles');
    //         $table->string('deception');
    //         $table->string('yandex_secret_id');
    //     });
    // }
    public function up(): void
    {
        Schema::create('role', function (Blueprint $table) {
            $table->id();
            $table->string('name_roles');
            $table->string('deception');
            $table->string('yandex_cloud_id');
            $table->string('yandex_cloud_secret_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role');
    }
};
