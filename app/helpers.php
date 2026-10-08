<?php

use Illuminate\Support\Carbon;

/*
 * Kleine helpers die overal in de app en in Blade gebruikt worden.
 * Geladen via "autoload.files" in composer.json.
 */

if (! function_exists('euro')) {
    /**
     * Bedragen staan in de database in centen. Gebruik deze helper om ze te tonen.
     * euro(350) => "€ 3,50"
     */
    function euro(int $centen): string
    {
        return '€ '.number_format($centen / 100, 2, ',', '.');
    }
}

if (! function_exists('kantineTijd')) {
    /**
     * Tijden staan in UTC in de database. Deze helper geeft de tijd in Nederland terug.
     */
    function kantineTijd(DateTimeInterface|string $tijd): Carbon
    {
        $tijd = $tijd instanceof DateTimeInterface
            ? Carbon::instance($tijd)
            : Carbon::parse($tijd, 'UTC');

        return $tijd->copy()->setTimezone('Europe/Amsterdam');
    }
}
