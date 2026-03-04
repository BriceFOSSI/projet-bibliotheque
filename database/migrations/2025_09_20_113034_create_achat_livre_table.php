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
        Schema::create('achat_livre', function (Blueprint $table) {
    $table->id();
    $table->foreignId('achat_id')->constrained()->onDelete('cascade');
    $table->foreignId('livre_id')->constrained()->onDelete('cascade');
    $table->integer('quantite');
    $table->decimal('prix_unitaire', 8, 2);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achat_livre');
    }
};
