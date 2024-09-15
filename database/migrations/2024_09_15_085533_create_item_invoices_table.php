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
        Schema::create('item_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('deskripsi')->nullable();
            $table->unsignedBigInteger('harga')->nullable()->default(0);
            $table->unsignedBigInteger('jumlah')->nullable()->default(0);
            $table->unsignedBigInteger('diskon')->nullable()->default(0);
            $table->unsignedBigInteger('total_harga')->nullable()->default(0);
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_invoices');
    }
};
