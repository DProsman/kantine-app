<?php

namespace App\Http\Controllers;

use App\Models\Bestelling;
use App\Models\Bestelregel;
use App\Models\Product;
use App\Models\Tijdslot;
use App\Services\Kassa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BestelController extends Controller
{
    public function index()
    {
        $categorieen = config('kantine.categorieen');

        return view('bestellen.index', [
            'categorieen' => $categorieen,
            'producten' => Product::beschikbaar()
                ->whereIn('categorie', array_keys($categorieen))
                ->orderBy('naam')
                ->get()
                ->groupBy('categorie'),
            'tijdsloten' => Tijdslot::vandaag()->orderBy('start')->get(),
        ]);
    }

    public function store(Request $request, Kassa $kassa)
    {
        $data = $request->validate([
            'tijdslot_id' => ['required', 'exists:tijdsloten,id'],
            'aantal' => ['required', 'array'],
            'aantal.*' => ['nullable', 'integer', 'min:0', 'max:10'],
            'opmerking' => ['nullable', 'string', 'max:255'],
        ]);

        $aantallen = array_filter($data['aantal'], fn ($aantal) => (int) $aantal > 0);

        if ($aantallen === []) {
            return back()->withErrors(['aantal' => 'Kies minstens één product.'])->withInput();
        }

        $bestelling = DB::transaction(function () use ($data, $aantallen, $kassa) {
            $bestelling = new Bestelling([
                'user_id' => auth()->id(),
                'tijdslot_id' => $data['tijdslot_id'],
                'opmerking' => $data['opmerking'] ?? null,
            ]);

            $regels = collect($aantallen)->map(function ($aantal, $productId) {
                $product = Product::beschikbaar()->findOrFail($productId);

                return new Bestelregel([
                    'product_id' => $product->id,
                    'aantal' => (int) $aantal,
                    'stukprijs' => $product->prijs,
                ]);
            })->values();

            // Eerst rekenen, dan in één keer opslaan.
            $bestelling->setRelation('regels', $regels);
            $kassa->berekenTotaal($bestelling)->save();
            $bestelling->regels()->saveMany($regels);

            return $bestelling;
        });

        return redirect()->route('bestellingen.index')
            ->with('melding', "Bestelling #{$bestelling->id} is geplaatst.");
    }
}
