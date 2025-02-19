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
        Schema::create('deces_hops', function (Blueprint $table) {
            $table->id();
             // les informations de sur le defunt
             $table->string('NomM');
             $table->string('PrM');
             $table->string('DateNaissance');
             $table->string('DateDeces');
             $table->string('Remarques');
             $table->string('nomHop');
             $table->string('commune');
             $table->string('codeDM')->default('rien')->unique();
             $table->string('codeCMD')->default('rien')->unique();
             $table->foreignId('sous_admin_id')->constrained()->onDelete('cascade'); // Ajout du docteur_id
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deces_hops');
    }
};
