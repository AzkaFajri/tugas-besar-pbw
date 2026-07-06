<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailProduct extends Model
{
    protected $table = 'detail_products';
    protected $primaryKey = ['id_po','id_product','size'];
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['id_po','id_product','qty','size','amount'];

    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();

        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getAttribute($keyName));
        }

        return $query;
    }

    public function getKeyName()
    {
        return $this->primaryKey;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_product');
    }
}
