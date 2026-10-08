<?php

namespace Tests\Feature;

use App\Models\Bestelling;
use App\Models\Product;
use App\Models\Tijdslot;
use App\Models\User;
use App\Services\Kassa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KassaTest extends TestCase
{
    use RefreshDatabase;

    private function maakBestelling(User $user): Bestelling
    {
        $product = Product::create(['naam' => 'Broodje kaas', 'prijs' => 250, 'categorie' => 'broodjes']);
        $tijdslot = Tijdslot::create(['start' => now()->utc()]);

        $bestelling = Bestelling::create(['user_id' => $user->id, 'tijdslot_id' => $tijdslot->id]);
        $bestelling->regels()->create(['product_id' => $product->id, 'aantal' => 4, 'stukprijs' => 250]);

        return $bestelling;
    }

    public function test_student_betaalt_volle_prijs(): void
    {
        $bestelling = $this->maakBestelling(User::factory()->create(['rol' => 'student']));

        (new Kassa)->berekenTotaal($bestelling);

        $this->assertSame(1000, $bestelling->subtotaal);
        $this->assertSame(0, $bestelling->korting);
        $this->assertSame(1000, $bestelling->totaal);
    }

    public function test_personeel_krijgt_vijftien_procent_korting(): void
    {
        $bestelling = $this->maakBestelling(User::factory()->create(['rol' => 'personeel']));

        (new Kassa)->berekenTotaal($bestelling);

        $this->assertSame(150, $bestelling->korting);
        $this->assertSame(850, $bestelling->totaal);
    }
}
