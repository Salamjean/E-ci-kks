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
        Schema::create('compte_demandes', function (Blueprint $table) {
            $table->id();
            $table->string('montant_timbre');
            $table->string('montant_livraison');
            $table->string('name');
            $table->string('prenom');
            $table->string('contact');
            $table->string('email');
            $table->string('adresse_livraison');
            $table->string('code_postal')->nullable();
            $table->string('ville');
            $table->string('commune');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compte_demandes');
    }
};
