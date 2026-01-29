<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contactos', function (Blueprint $table) {
            $table->id();

            $table->string('numero')->nullable();

            $table->foreignId('entidade_id')
                ->constrained('entidades')
                ->cascadeOnDelete();

            $table->string('nome');
            $table->string('apelido');

            $table->foreignId('funcao_id')
                ->nullable()
                ->constrained('funcoes')
                ->nullOnDelete();

            $table->string('telefone')->nullable();
            $table->string('telemovel')->nullable();
            $table->string('email')->nullable();

            $table->boolean('consentimento_rgpd')->default(false);

            $table->text('observacoes')->nullable();

            $table->boolean('estado')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contactos');
    }
};
