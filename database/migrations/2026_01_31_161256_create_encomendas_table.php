<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('encomendas', function (Blueprint $table) {
            $table->id();

            $table->string('numero')->unique();
            $table->date('data');

            
            $table->foreignId('entidade_id')
                ->constrained('entidades')
                ->cascadeOnDelete();

            $table->decimal('valor_total', 10, 2)->default(0);

            $table->enum('estado', ['Rascunho', 'Fechado'])
                ->default('Rascunho');

            
            $table->foreignId('proposta_id')
                ->nullable()
                ->constrained('propostas')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('encomendas');
    }
};
