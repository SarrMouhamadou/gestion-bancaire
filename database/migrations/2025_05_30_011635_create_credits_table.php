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
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->decimal('montant', 15, 2);
            $table->decimal('taux_interet', 5, 2);
            $table->integer('duree_mois');
            $table->decimal('mensualite', 15, 2);
            $table->date('date_demande');
            $table->string('statut')->default('en_cours');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
