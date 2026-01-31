<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('proposta_linhas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('proposta_id')->constrained()->cascadeOnDelete();
            $table->foreignId('artigo_id')->constrained();
            $table->foreignId('fornecedor_id')->nullable()->constrained('entidades');

            $table->integer('quantidade')->default(1);

            $table->decimal('preco_unitario', 10, 2);
            $table->decimal('preco_custo', 10, 2)->nullable();

            $table->decimal('iva_percentagem', 5, 2);

            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposta_linhas');
    }
};
