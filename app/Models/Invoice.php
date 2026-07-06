<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $primaryKey = 'id_invoice';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id_invoice','id_po','tgl_invoice','id_customer','id_pegawai','subtotal_invoice','ppn_invoice','grand_total_invoice','notes','id_user'];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'id_po', 'id_po');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
