<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fatura_fornecedor_linhas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fatura_fornecedor_id')
                ->constrained('fatura_fornecedors')
                ->cascadeOnDelete();

            $table->string('descricao');

            $table->integer('quantidade')->default(1);

            $table->decimal('preco_unitario', 10, 2);
            $table->decimal('iva_percentagem', 5, 2);
            $table->decimal('iva_valor', 10, 2);

            $table->decimal('preco_total', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fatura_fornecedor_linhas');
    }
};
