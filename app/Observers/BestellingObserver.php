<?php

namespace App\Observers;

use App\Models\Bestelling;
use App\Services\Kassa;

class BestellingObserver
{
    /**
     * Hotfix: bij aangepaste bestellingen werd de personeelskorting niet verrekend.
     * Daarom passen we de korting toe zodra een bestelling wordt bijgewerkt.
     */
    public function updating(Bestelling $bestelling): void
    {
        if ($bestelling->user->krijgtPersoneelskorting()) {
            $korting = (int) round($bestelling->subtotaal * Kassa::PERSONEELSKORTING);

            $bestelling->korting = $korting;
            $bestelling->totaal = $bestelling->subtotaal - $korting;
        }
    }
}
