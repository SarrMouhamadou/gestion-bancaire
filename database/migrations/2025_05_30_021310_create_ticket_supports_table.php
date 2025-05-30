<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use SebastianBergmann\CliParser\OptionDoesNotAllowArgumentException;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ticket_supports', function (Blueprint $table) {
            $table->id();
            $table->string('sujet');
            $table->text('description');
            $table->timestamp('date_ouverture');
            $table->string('statut')->default('ouvert');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_supports');
    }
};
