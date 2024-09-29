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
        Schema::create('invoices', function (Blueprint $table) {
            $table->string('no_invoice')->primary()->unique();
            $table->date('tanggal');
            $table->time('jam');
            $table->string('tenaga_medis');
            $table->string('poli');
            $table->string('nama_pasien');
            $table->string('metode_pembayaran')->nullable();
            $table->enum('status', ['Lunas', 'Belum Lunas'])->nullable();
            $table->unsignedBigInteger('terbayar')->nullable();
            $table->unsignedBigInteger('sisa_hutang')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->text('catatan_pasien')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
