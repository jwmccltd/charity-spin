
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('music_genres', function (Blueprint $table) {
            $table->id();

            // Parent genre or top-level category
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('music_genres')
                ->nullOnDelete();

            // Genre details
            $table->string('name');
            $table->string('slug')->unique();

            // Optional ordering and visibility
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['parent_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('music_genres');
    }
};

