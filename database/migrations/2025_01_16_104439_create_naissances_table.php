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
        Schema::create('naissances', function (Blueprint $table) {
            $table->id();
            $table->string('nomHopital');
            $table->string('nomDefunt');
            $table->string('dateNaiss');
            $table->string('lieuNaiss');
            $table->string('identiteDeclarant', 255)->nullable();
            $table->string('cdnaiss', 255)->nullable();
            $table->string('acteMariage', 255)->nullable();
            $table->string('commune')->nullable();
            $table->string('nom');
            $table->string('prenom');
            $table->string('nompere');
            $table->string('prenompere');
            $table->string('datepere');
            $table->boolean('is_read')->default(false); // Statut de lecture
            $table->string('etat')->default('en attente'); // État par défaut
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Ajout de user_id
            $table->foreignId('agent_id')->nullable()->constrained('agents')->onDelete('set null'); // Ajout de agent_id

            //informations de livraison 
            $table->string('montant_timbre')->nullable();
            $table->string('montant_livraison')->nullable();
            $table->string('nom_destinataire')->nullable();
            $table->string('prenom_destinataire')->nullable();
            $table->string('email_destinataire')->nullable();
            $table->string('contact_destinataire')->nullable();
            $table->string('adresse_livraison')->nullable();
            $table->string('choix_option');
            $table->string('code_postal')->nullable();
            $table->string('ville')->nullable();
            $table->string('commune_livraison')->nullable();
            $table->string('quartier')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('naissances');
    }
};
