<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('film_acteur', function (Blueprint $table) {
            $table->foreignId('film_id')->constrained()->onDelete('cascade');
            $table->foreignId('acteur_id')->constrained()->onDelete('cascade');
            $table->boolean('role_principal')->default(false);
            $table->string('role_nom')->nullable();
            $table->primary(['film_id', 'acteur_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('film_acteur');
    }
};
