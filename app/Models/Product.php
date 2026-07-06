<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id_product';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = ['id_product','description_product','unit_price'];

    public static function generateNextId(): string
    {
        $prefix = 'PR';
        $nextNumber = static::selectRaw("MAX(CAST(SUBSTRING(id_product, 3) AS UNSIGNED)) as max_num")->value('max_num');
        $nextNumber = intval($nextNumber) + 1;
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
