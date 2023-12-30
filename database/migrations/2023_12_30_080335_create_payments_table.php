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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->references('id')->on('orders')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->dateTime('payment_time');
            $table->string('payment_type');
            $table->integer('amount');
            $table->enum('status', ['pending', 'success', 'failed']);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
