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
        Schema::create('frais_bancaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compte_id')->constrained('comptes')->onDelete('cascade');
            $table->string('type');
            $table->decimal('montant', 15, 2);
            $table->date('date');
            $table->string('statut')->default('applique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frais_bancaires');
    }
};
