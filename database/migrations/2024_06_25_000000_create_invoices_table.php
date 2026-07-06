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
            $table->string('id_invoice', 50)->primary();
            $table->string('id_po', 20);
            $table->date('tgl_invoice');
            $table->string('id_customer');
            $table->string('id_pegawai');
            $table->decimal('subtotal_invoice', 15, 2)->default(0);
            $table->decimal('ppn_invoice', 15, 2)->default(0);
            $table->decimal('grand_total_invoice', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            
            $table->index('id_po');
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
