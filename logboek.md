## Ticket #101
**Reproductie:** Ik heb gekeken naar wat Ria kon, vervolgens wat statussen aangepast, de prijs bij alleen het persooneel zakte steeds verder bij elke update die de bestelling kreeg.
**Hypothese:** Functionaliteit rondom persooneelskorting berekenen.
**Onderzoek:** begonnen bij de controller, model en routes om te kijken waar de korting berekent wordt, dat was in de BestellingObserver.
**Oorzaak:** In deze Observer werdt bij elke update de korting van het totaal afgehaald en werdt dat het nieuwe totaal, wat er voor zorgde dat het totaal bedrag steeds lager werd(personeelskorting blijft hetzelfde).
**Oplossing:** Uiteindelijk hoefde alleen dit stukje code($bestelling->totaal = $bestelling->totaal - $korting;) verandert te worden naar($bestelling->totaal = $bestelling->subtotaal - $korting;) zodat de korting van het subtotaal afgetrokken wordt in plaats van steeds een nieuw totaal te maken.

## Ticket #102
**Reproductie:** Nieuwe bestellingen plaatsen om verschillende tijden, bepaalde bestelling om specifieke tijden verdwenen.
**Hypothese:** Het lijkt erop dat het specifiek misgaat bij het ophalen van de bestellingen voor de index beheer pagina, de gebruiker kan zelf namelijk wel zijn bestelling zien.
**Onderzoek:** Ik ben begonnen bij de view zelf en ben gewoon terug gaan werken in de logica tot ik de plek vondt, view->route->controller->model
**Oorzaak:** Het probleem bleek in de model te zitten, bij de functie scopeTussen(). hij haalt daat als begin tijd alle bestellingen op die boven een bepaalde tijd zitten in plaats van alle bestellingen die er boven zitten en de gene die er gelijk mee zijn.
**Oplossing:** Ik heb deze zin: return $query->where('start', '>', $vanUtc) verandert naar: return $query->where('start', '>=', $vanUtc)

## Ticket #103
**Reproductie:** Nieuw broodje toegevoegd, verdween inderdaad.
**Hypothese:** iets in de controller
**Onderzoek:** Controller, model
**Oorzaak:** De fillable in de model
**Oplossing:** Deze zin: protected $fillable = ['naam', 'prijs', 'beschikbaar']; naar dit: protected $fillable = ['naam', 'prijs', 'categorie', 'beschikbaar']; verandert