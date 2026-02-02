<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fatura_fornecedors', function (Blueprint $table) {
            $table->id();

            $table->string('numero')->unique();
            $table->date('data');

            $table->foreignId('fornecedor_id')
                ->constrained('entidades')
                ->cascadeOnDelete();

            $table->decimal('valor_total', 10, 2)->default(0);

            $table->enum('estado', [
                'Rascunho',
                'Validada',
                'Paga'
            ])->default('Rascunho');

            $table->string('pdf_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fatura_fornecedors');
    }
};
