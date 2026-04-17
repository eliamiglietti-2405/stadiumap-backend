<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('home_match_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('stadium_id')->constrained('stadiums')->cascadeOnDelete();
            $table->string('fixture_id');
            $table->timestamp('match_date');
            $table->timestamps();

            $table->unique(['user_id', 'fixture_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_match_notifications');
    }
};