<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('empresa_settings', function (Blueprint $table) {
            $table->id();

            $table->string('nome');
            $table->string('nif')->unique();
            $table->string('morada');
            $table->string('codigo_postal'); // XXXX-XXX
            $table->string('localidade');

            $table->string('logotipo_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresa_settings');
    }
};
