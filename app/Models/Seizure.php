<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Seizure extends Model
{
    use HasFactory;
    protected $fillable = [
        'products_id',
        'cliente_id',
        'sucursal',
        'recibido_por',
        'accesorio',
        'obs_product',
        'factura',
        'fecha_factura',
        'cost_price',
        'quantity',
        'monto_facturado',
        'sale_price',
        'fecha_decomiso',
        'sale_quota',
        'observation_pm',
        'monto_cancelado',
        'saldo',
        'fecha_entrega',
        'area',
        'status',
        'status_producto',
        'suggested_price',
        'obs_almacen',
        'attachment'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');


    }
    public function canEdit(): bool
    {
        return $this->estado !== 'Reventa';
    }


    public function cliente()
    {
        return $this->belongsTo(\App\Models\Client::class);
    }



}
