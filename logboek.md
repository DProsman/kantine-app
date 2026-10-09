## Ticket #101
**Reproductie:** Ik heb gekeken naar wat Ria kon, vervolgens wat statussen aangepast, de prijs bij alleen het persooneel zakte steeds verder bij elke update die de bestelling kreeg.
**Hypothese:** Functionaliteit rondom persooneelskorting berekenen.
**Onderzoek:** begonnen bij de controller, model en routes om te kijken waar de korting berekent wordt, dat was in de BestellingObserver.
**Oorzaak:** In deze Observer werdt bij elke update de korting van het totaal afgehaald en werdt dat het nieuwe totaal, wat er voor zorgde dat het totaal bedrag steeds lager werd(personeelskorting blijft hetzelfde).
**Oplossing:** Uiteindelijk hoefde alleen dit stukje code($bestelling->totaal = $bestelling->totaal - $korting;) verandert te worden naar($bestelling->totaal = $bestelling->subtotaal - $korting;) zodat de korting van het subtotaal afgetrokken wordt in plaats van steeds een nieuw totaal te maken.

## Ticket #102
**Reproductie:** welke stappen, wat zag ik, wat verwachtte ik?
**Hypothese:** waar denk ik dat het misgaat en waarom?
**Onderzoek:** welke bestanden/commits heb ik bekeken, wat sloot ik uit?
**Oorzaak:** wat bleek het echt te zijn?
**Oplossing:** wat heb ik veranderd en waarom zo?

## Ticket #103
**Reproductie:** welke stappen, wat zag ik, wat verwachtte ik?
**Hypothese:** waar denk ik dat het misgaat en waarom?
**Onderzoek:** welke bestanden/commits heb ik bekeken, wat sloot ik uit?
**Oorzaak:** wat bleek het echt te zijn?
**Oplossing:** wat heb ik veranderd en waarom zo?