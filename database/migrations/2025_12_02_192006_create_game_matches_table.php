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
    Schema::create('game_matches', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
        $table->foreignId('home_team_id')->nullable()->constrained('teams');
        $table->foreignId('away_team_id')->nullable()->constrained('teams');
        $table->foreignId('winner_id')->nullable()->constrained('teams');
        $table->string('round');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_matches');
    }
};
