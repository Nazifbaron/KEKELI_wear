<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/* ============================================================
   ORDER ITEM — Snapshot produit dans la commande
============================================================ */
class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'product_name', 'category_name',
        'unit_price', 'quantity', 'subtotal', 'is_custom',
    ];

    protected $casts = [
        'unit_price' => 'decimal:0',
        'subtotal'   => 'decimal:0',
        'is_custom'  => 'boolean',
    ];

    public function order()   { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
