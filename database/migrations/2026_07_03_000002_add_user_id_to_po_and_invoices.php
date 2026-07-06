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
        // Add user_id to purchase_order table
        if (Schema::hasTable('purchase_order')) {
            Schema::table('purchase_order', function (Blueprint $table) {
                if (!Schema::hasColumn('purchase_order', 'id_user')) {
                    $table->foreignId('id_user')->nullable()->constrained('users', 'id')->after('id_po');
                }
            });
        }

        // Add user_id to invoices table
        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                if (!Schema::hasColumn('invoices', 'id_user')) {
                    $table->foreignId('id_user')->nullable()->constrained('users', 'id')->after('id_invoice');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('purchase_order')) {
            Schema::table('purchase_order', function (Blueprint $table) {
                if (Schema::hasColumn('purchase_order', 'id_user')) {
                    $table->dropForeign(['id_user']);
                    $table->dropColumn('id_user');
                }
            });
        }

        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                if (Schema::hasColumn('invoices', 'id_user')) {
                    $table->dropForeign(['id_user']);
                    $table->dropColumn('id_user');
                }
            });
        }
    }
};
