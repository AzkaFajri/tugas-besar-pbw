<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = ['id_pegawai','nama_pegawai','alamat_pegawai','hp_pegawai','jabatan'];

    public static function generateNextId(): string
    {
        $prefix = 'PG';
        $nextNumber = static::selectRaw("MAX(CAST(SUBSTRING(id_pegawai, 3) AS UNSIGNED)) as max_num")->value('max_num');
        $nextNumber = intval($nextNumber) + 1;
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
