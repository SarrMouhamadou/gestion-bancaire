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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->decimal('montant', 15, 2);
            $table->timestamp('date');
            $table->foreignId('compte_source_id')->constrained('comptes')->onDelete('cascade');
            $table->foreignId('compte_dest_id')->nullable()->constrained('comptes')->onDelete('cascade');
            $table->string('statut')->default('valide');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
