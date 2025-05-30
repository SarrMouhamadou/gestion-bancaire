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
        Schema::create('carte_bancaires', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->string('cvv', 3);
            $table->date('date_expiration');
            $table->decimal('solde', 15, 2)->default(0);
            $table->string('statut')->default('active');
            $table->foreignId('compte_id')->constrained('comptes')->onDelete('cascade');
            $table->string('code_pin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carte_bancaires');
    }
};
