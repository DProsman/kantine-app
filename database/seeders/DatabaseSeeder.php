<?php

namespace Database\Seeders;

use App\Models\Bestelling;
use App\Models\Bestelregel;
use App\Models\Product;
use App\Models\Tijdslot;
use App\Models\User;
use App\Services\Kassa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $ria = User::create(['name' => 'Ria (kantinebeheer)', 'email' => 'ria@kantine.test', 'password' => 'wachtwoord', 'rol' => 'beheerder']);
        $dejong = User::create(['name' => 'Dhr. De Jong', 'email' => 'dejong@kantine.test', 'password' => 'wachtwoord', 'rol' => 'personeel']);
        $yilmaz = User::create(['name' => 'Mevr. Yilmaz', 'email' => 'yilmaz@kantine.test', 'password' => 'wachtwoord', 'rol' => 'personeel']);
        $fatima = User::create(['name' => 'Fatima', 'email' => 'fatima@kantine.test', 'password' => 'wachtwoord', 'rol' => 'student']);
        $daan = User::create(['name' => 'Daan', 'email' => 'daan@kantine.test', 'password' => 'wachtwoord', 'rol' => 'student']);
        $joris = User::create(['name' => 'Joris', 'email' => 'joris@kantine.test', 'password' => 'wachtwoord', 'rol' => 'student', 'werkt_bij_kantine' => true]);

        // Bulk insert is sneller dan losse creates.
        $nu = now();
        Product::insert(collect([
            ['Broodje kaas', 250, 'broodjes'],
            ['Broodje ham-kaas', 295, 'broodjes'],
            ['Broodje kip-kerrie', 350, 'broodjes'],
            ['Broodje hummus', 325, 'broodjes'],
            ['Tomatensoep', 220, 'soep'],
            ['Erwtensoep', 260, 'soep'],
            ['Saucijzenbroodje', 195, 'snacks'],
            ['Appelflap', 175, 'snacks'],
            ['Koffie', 120, 'drinken'],
            ['Thee', 100, 'drinken'],
            ['Chocomel', 160, 'drinken'],
            ['Water 500 ml', 140, 'drinken'],
        ])->map(fn ($p) => [
            'naam' => $p[0], 'prijs' => $p[1], 'categorie' => $p[2],
            'beschikbaar' => true, 'created_at' => $nu, 'updated_at' => $nu,
        ])->all());

        $vandaag = Carbon::today('Europe/Amsterdam');
        $sloten = [];
        foreach (['10:00', '10:15', '12:00', '12:15', '12:30', '12:45'] as $tijd) {
            $sloten[$tijd] = Tijdslot::create([
                'start' => $vandaag->copy()->setTimeFromTimeString($tijd)->utc(),
                'capaciteit' => 20,
            ]);
        }

        $this->bestel($joris, $sloten['10:00'], ['Koffie' => 1, 'Appelflap' => 1]);
        $this->bestel($yilmaz, $sloten['10:15'], ['Thee' => 2]);
        $this->bestel($fatima, $sloten['12:00'], ['Broodje hummus' => 2, 'Water 500 ml' => 1]);
        $this->bestel($daan, $sloten['12:00'], ['Saucijzenbroodje' => 3]);
        $this->bestel($dejong, $sloten['12:15'], ['Broodje kip-kerrie' => 2, 'Tomatensoep' => 1], 'klaar');
        $this->bestel($fatima, $sloten['12:30'], ['Chocomel' => 1], 'afgehaald');
        $this->bestel($daan, $sloten['12:45'], ['Broodje kaas' => 1, 'Koffie' => 1]);
    }

    /**
     * Maakt een bestelling op dezelfde manier als de bestelpagina dat doet.
     */
    private function bestel(User $user, Tijdslot $tijdslot, array $producten, ?string $status = null): void
    {
        $bestelling = new Bestelling(['user_id' => $user->id, 'tijdslot_id' => $tijdslot->id]);

        $regels = collect($producten)->map(function ($aantal, $naam) {
            $product = Product::where('naam', $naam)->firstOrFail();

            return new Bestelregel(['product_id' => $product->id, 'aantal' => $aantal, 'stukprijs' => $product->prijs]);
        })->values();

        $bestelling->setRelation('regels', $regels);
        app(Kassa::class)->berekenTotaal($bestelling)->save();
        $bestelling->regels()->saveMany($regels);

        if ($status) {
            $bestelling->update(['status' => $status]);
        }
    }
}
