<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        \DB::statement('ALTER TABLE detail_products DROP FOREIGN KEY detail_products_ibfk_1');
        \DB::statement('ALTER TABLE detail_products DROP FOREIGN KEY detail_products_ibfk_2');
        \DB::statement('ALTER TABLE detail_products DROP PRIMARY KEY');
        \DB::statement('ALTER TABLE detail_products ADD PRIMARY KEY (id_po, id_product, size)');
        \DB::statement('ALTER TABLE detail_products ADD CONSTRAINT detail_products_ibfk_1 FOREIGN KEY (id_po) REFERENCES purchase_order(id_po)');
        \DB::statement('ALTER TABLE detail_products ADD CONSTRAINT detail_products_ibfk_2 FOREIGN KEY (id_product) REFERENCES products(id_product)');
    }

    public function down(): void
    {
        \DB::statement('ALTER TABLE detail_products DROP FOREIGN KEY detail_products_ibfk_1');
        \DB::statement('ALTER TABLE detail_products DROP FOREIGN KEY detail_products_ibfk_2');
        \DB::statement('ALTER TABLE detail_products DROP PRIMARY KEY');
        \DB::statement('ALTER TABLE detail_products ADD PRIMARY KEY (id_po, id_product)');
        \DB::statement('ALTER TABLE detail_products ADD CONSTRAINT detail_products_ibfk_1 FOREIGN KEY (id_po) REFERENCES purchase_order(id_po)');
        \DB::statement('ALTER TABLE detail_products ADD CONSTRAINT detail_products_ibfk_2 FOREIGN KEY (id_product) REFERENCES products(id_product)');
    }
};
