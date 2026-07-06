<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'id_customer';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = ['id_customer','nama_customer','alamat_customer','hp_customer'];

    public static function generateNextId(): string
    {
        $prefix = 'CS';
        $nextNumber = static::selectRaw("MAX(CAST(SUBSTRING(id_customer, 3) AS UNSIGNED)) as max_num")->value('max_num');
        $nextNumber = intval($nextNumber) + 1;
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
