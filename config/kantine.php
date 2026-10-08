<?php

return [

    /*
     * Categorieën die op de bestelpagina getoond worden (sleutel => kopje).
     * Producten in een andere categorie zijn wel in beheer zichtbaar,
     * maar kunnen niet besteld worden.
     */
    'categorieen' => [
        'broodjes' => 'Broodjes',
        'soep' => 'Soep',
        'snacks' => 'Snacks',
        'drinken' => 'Drinken',
    ],

    /*
     * Pauzes in Nederlandse tijd: [van, tot). Gebruikt in het beheeroverzicht.
     */
    'pauzes' => [
        'Kleine pauze' => ['10:00', '10:30'],
        'Grote pauze' => ['12:00', '13:00'],
    ],

];
