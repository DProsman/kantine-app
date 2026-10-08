# Kantine-app

Bestelsysteem voor de schoolkantine: studenten en personeel bestellen vooraf
en kiezen een afhaaltijdslot. Laravel 12, SQLite, Blade (geen Vite/npm nodig).

## Installatie

Vereist: PHP 8.2 of hoger en Composer.

```bash
composer install
cp .env.example .env            # Windows: copy .env.example .env
php artisan key:generate
php artisan migrate --seed      # beantwoord de vraag over database.sqlite met "yes"
php artisan serve
```

Open http://127.0.0.1:8000 en kies een demo-gebruiker. Opnieuw beginnen met
schone demodata: `php artisan migrate:fresh --seed`.

De demodata maakt tijdsloten aan voor **de dag waarop je seedt**. Seed dus
opnieuw als je de volgende dag verder werkt.

Tests draaien: `php artisan test`

## Afspraken in deze codebase

- **Bedragen staan in centen** (integer). Tonen gaat altijd via `euro($centen)`.
- **Tijden staan in UTC** in de database. Tonen gaat via `kantineTijd($tijd)`.
- Totalen worden berekend door `App\Services\Kassa`. De Kassa slaat niet zelf op.
- Instellingen van de kantine (categorieën, pauzes) staan in `config/kantine.php`.
- Tabellen en modellen hebben Nederlandse namen (`producten`, `tijdsloten`, ...).

## Rollen

| Rol        | Mag                                              |
|------------|--------------------------------------------------|
| student    | bestellen, eigen open bestellingen aanpassen     |
| personeel  | idem, met 15% personeelskorting                  |
| beheerder  | overzicht per pauze, status wijzigen, producten  |

Studenten met `werkt_bij_kantine = true` krijgen ook personeelskorting.
