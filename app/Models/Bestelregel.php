<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bestelregel extends Model
{
    protected $table = 'bestelregels';

    protected $fillable = ['bestelling_id', 'product_id', 'aantal', 'stukprijs'];

    protected function casts(): array
    {
        return [
            'aantal' => 'integer',
            'stukprijs' => 'integer',
        ];
    }

    public function bestelling(): BelongsTo
    {
        return $this->belongsTo(Bestelling::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function regelTotaal(): int
    {
        return $this->aantal * $this->stukprijs;
    }
}
