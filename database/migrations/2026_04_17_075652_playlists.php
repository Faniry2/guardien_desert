<?php
// database/migrations/xxxx_create_tracks_and_playlists_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Playlists ─────────────────────────────────────────────
        Schema::create('playlists', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // ex: Méditation Profonde
            $table->string('slug')->unique();                // ex: meditation-profonde
            $table->text('description')->nullable();
            $table->enum('type', ['ambiance', 'module']);    // ambiance | module
            $table->string('category')->nullable();          // ex: meditation, sommeil, focus
            $table->string('module')->nullable();            // ex: reset, reboot, clarte
            $table->string('cover')->nullable();             // image de couverture
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Pistes audio ──────────────────────────────────────────
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->string('title');                         // ex: Vent du Sahara
            $table->string('slug')->unique();                // ex: vent-du-sahara
            $table->string('filename');                      // ex: vent-du-sahara.mp3
            $table->string('category')->nullable();          // ambiance: meditation | module: reset
            $table->string('module')->nullable();            // reset | reboot | clarte...
            $table->integer('duration_seconds')->default(0); // durée en secondes
            $table->string('duration_label')->nullable();    // ex: 12:34
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Pivot playlist_track ──────────────────────────────────
        Schema::create('playlist_track', function (Blueprint $table) {
            $table->id();
            $table->foreignId('playlist_id')->constrained()->onDelete('cascade');
            $table->foreignId('track_id')->constrained()->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('playlist_track');
        Schema::dropIfExists('tracks');
        Schema::dropIfExists('playlists');
    }
};