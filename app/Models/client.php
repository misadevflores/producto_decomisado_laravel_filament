<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = ['nombre'];
    protected $table = 'clientes';

    public function seizures()
    {
        return $this->hasMany(Seizure::class, 'cliente_id');
    }


}
