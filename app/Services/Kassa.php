<?php

namespace App\Services;

use App\Models\Bestelling;

/**
 * Berekent subtotaal, korting en totaal van een bestelling.
 * Let op: de Kassa slaat NIET op, dat doet de aanroeper.
 */
class Kassa
{
    public const PERSONEELSKORTING = 0.15;

    public function berekenTotaal(Bestelling $bestelling): Bestelling
    {
        $subtotaal = $bestelling->regels->sum(fn ($regel) => $regel->regelTotaal());

        $korting = $bestelling->user->krijgtPersoneelskorting()
            ? (int) round($subtotaal * self::PERSONEELSKORTING)
            : 0;

        $bestelling->subtotaal = $subtotaal;
        $bestelling->korting = $korting;
        $bestelling->totaal = $subtotaal - $korting;

        return $bestelling;
    }
}
