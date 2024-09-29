<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    // Remove 'id' from the guarded fields and set the primary key as 'no_invoice'
    protected $primaryKey = 'no_invoice';
    public $incrementing = false; // Since it's not an auto-incrementing primary key
    protected $keyType = 'string'; // Because 'no_invoice' is a string

    protected $guarded = [];

    public $timestamps = false;

    public function items(): HasMany
    {
        return $this->hasMany(ItemInvoice::class, 'invoice_no', 'no_invoice');
    }
}
