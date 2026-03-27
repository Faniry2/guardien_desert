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
        Schema::create('user_encryption_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('salt_hex', 64);         // Salt PBKDF2 (32 bytes hex)
            $table->string('key_check_hash', 256);  // HMAC-SHA256 base64
            $table->string('hint', 255)->nullable(); // Indice optionnel (en clair)
            $table->boolean('is_configured')->default(false);
            $table->timestamp('configured_at')->nullable();
            $table->timestamps();
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_encryption_keys');
    }
};
