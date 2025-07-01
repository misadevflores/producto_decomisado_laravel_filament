<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;
class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'descripcion',
        'sku',
        'modelo',
        'costo',
        'precio_venta',
        'stock',
    ];
    public function seizures()
    {
        return $this->hasMany(Seizure::class);
    }
}
