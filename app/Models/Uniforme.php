<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uniforme extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'categoria', 'tipo'];

    public function fotos()
    {
        return $this->hasMany(UniformeFoto::class);
    }
}