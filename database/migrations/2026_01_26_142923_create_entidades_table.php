<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('entidades', function (Blueprint $table) {
            $table->id();

            
            $table->boolean('is_cliente')->default(false);
            $table->boolean('is_fornecedor')->default(false);

            
            $table->unsignedBigInteger('numero')->unique();

            
            $table->string('nif')->unique();
            $table->string('nome');

            
            $table->string('morada')->nullable();
            $table->string('codigo_postal', 8)->nullable();
            $table->string('localidade')->nullable();
            $table->foreignId('pais_id')->nullable()->constrained('paises');

            
            $table->string('telefone')->nullable();
            $table->string('telemovel')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();

            
            $table->boolean('rgpd')->default(false);

            
            $table->text('observacoes')->nullable();


            $table->enum('estado', ['ativo', 'inativo'])->default('ativo');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entidades');
    }
};
