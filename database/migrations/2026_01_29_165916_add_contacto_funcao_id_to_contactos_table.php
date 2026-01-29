<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    Schema::table('contactos', function (Blueprint $table) {

        
        if (Schema::hasColumn('contactos', 'funcao_id')) {
            $table->dropForeign(['funcao_id']);
            $table->dropColumn('funcao_id');
        }

        
        $table->foreignId('contacto_funcao_id')
            ->nullable()
            ->after('apelido')
            ->constrained('contacto_funcoes')
            ->nullOnDelete();
    });
}


    public function down(): void
    {
        Schema::table('contactos', function (Blueprint $table) {
            $table->dropForeign(['contacto_funcao_id']);
            $table->dropColumn('contacto_funcao_id');
        });
    }
};
