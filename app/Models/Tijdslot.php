<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Tijdslot extends Model
{
    protected $table = 'tijdsloten';

    protected $fillable = ['start', 'capaciteit'];

    protected function casts(): array
    {
        return [
            'start' => 'datetime',
        ];
    }

    public function bestellingen(): HasMany
    {
        return $this->hasMany(Bestelling::class);
    }

    /**
     * Alle tijdsloten van vandaag (Nederlandse tijd).
     */
    public function scopeVandaag(Builder $query): Builder
    {
        $vandaag = Carbon::today('Europe/Amsterdam');

        return $query->where('start', '>=', $vandaag->copy()->utc())
            ->where('start', '<', $vandaag->copy()->addDay()->utc());
    }

    /**
     * Tijdsloten van vandaag tussen twee kloktijden (Nederlandse tijd), bv. '12:00' en '13:00'.
     */
    public function scopeTussen(Builder $query, string $van, string $tot): Builder
    {
        $vandaag = Carbon::today('Europe/Amsterdam');
        $vanUtc = $vandaag->copy()->setTimeFromTimeString($van)->utc();
        $totUtc = $vandaag->copy()->setTimeFromTimeString($tot)->utc();

        return $query->where('start', '>=', $vanUtc)
            ->where('start', '<', $totUtc);
    }

    public function label(): string
    {
        $start = kantineTijd($this->start);

        return $start->format('H:i').'–'.$start->copy()->addMinutes(15)->format('H:i');
    }
}
