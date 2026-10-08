<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'producten';

    protected $fillable = ['naam', 'prijs', 'beschikbaar'];

    protected function casts(): array
    {
        return [
            'prijs' => 'integer',
            'beschikbaar' => 'boolean',
        ];
    }

    public function scopeBeschikbaar(Builder $query): Builder
    {
        return $query->where('beschikbaar', true);
    }
}
