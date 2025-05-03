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
        Schema::create('shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Propriétaire
            $table->string('token')->unique(); // Jeton de partage
            $table->string('shareable_type'); // Type (File ou Folder)
            $table->unsignedBigInteger('shareable_id'); // ID du fichier ou dossier
            $table->enum('permission', ['read', 'write', 'admin'])->default('read');
            $table->foreignId('recipient_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['shareable_type', 'shareable_id']); // Crée un index composite qui améliore les performances des requêtes qui filtrent ou trient en fonction de ces colonnes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shares');
    }
};
