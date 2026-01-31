<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('propostas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('numero')->unique();
            $table->foreignId('cliente_id')->constrained('entidades');

            $table->date('data_proposta')->nullable();
            $table->date('validade');

            $table->enum('estado', ['rascunho', 'fechado'])->default('rascunho');

            $table->decimal('total', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('propostas');
    }
};

