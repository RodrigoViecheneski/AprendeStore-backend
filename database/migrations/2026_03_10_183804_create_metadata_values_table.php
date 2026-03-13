<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CategoryMetadata;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('metadata_values', function (Blueprint $table) {
            $table->string('id')->primary(); //preto, azul, branco, etc
            $table->string('label'); //cor
            $table->foreignIdFor(CategoryMetadata::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metadata_values');
    }
};
