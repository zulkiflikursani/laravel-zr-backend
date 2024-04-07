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
        Schema::create('pembelian', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company');
            $table->string('kode_pembelian');
            $table->date('tanggal_transaksi');
            $table->string('id_customer')->nullable(true);
            $table->string('product_id');
            $table->string('nama_product');
            $table->string('hbeli');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};
