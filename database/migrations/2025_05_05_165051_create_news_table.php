<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('contenido');
            $table->string('imagen')->nullable();
            $table->date('fecha');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};

