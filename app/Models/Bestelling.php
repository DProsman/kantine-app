<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bestelling extends Model
{
    protected $table = 'bestellingen';

    protected $fillable = [
        'user_id',
        'tijdslot_id',
        'status',
        'subtotaal',
        'korting',
        'totaal',
        'opmerking',
    ];

    protected function casts(): array
    {
        return [
            'subtotaal' => 'integer',
            'korting' => 'integer',
            'totaal' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tijdslot(): BelongsTo
    {
        return $this->belongsTo(Tijdslot::class);
    }

    public function regels(): HasMany
    {
        return $this->hasMany(Bestelregel::class);
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }
}
