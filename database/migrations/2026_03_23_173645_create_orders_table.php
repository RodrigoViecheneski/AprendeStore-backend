<?php

use App\Models\User;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('status')->default('pending');
            $table->float('total')->default(0);
            $table->float('shippingCost')->default(0);
            $table->integer('shippingDays')->default(0);
            $table->string('shippingZipcode')->nullable();
            $table->string('shippingStreet')->nullable();
            $table->string('shippingNumber')->nullable();
            $table->string('shippingCity')->nullable();
            $table->string('shippingState')->nullable();
            $table->string('shippingCountry')->nullable();
            $table->string('shippingComplement')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
