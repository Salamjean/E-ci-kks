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
        Schema::create('cgrae_search_history', function (Blueprint $table) {
            $table->id();
            $table->string('agent_name')->nullable();
            $table->string('agent_prenom')->nullable();
            $table->string('defunt_nom')->nullable();
            $table->string('defunt_prenom')->nullable();
            $table->string('codeCMD')->nullable();
            $table->string('search_term')->nullable();
            $table->unsignedBigInteger('cnpsagent_id')->nullable(); // Clé étrangère
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cgrae_search_history');
    }
};
