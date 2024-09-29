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
            $table->string('invoice_no');
            $table->foreign('invoice_no')->references('no_invoice')->on('invoices')->cascadeOnDelete();
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
