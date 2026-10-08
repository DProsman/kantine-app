<?php

namespace App\Http\Controllers;

use App\Models\Bestelling;
use App\Models\Tijdslot;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BeheerController extends Controller
{
    public function index()
    {
        $overzicht = [];
        foreach (config('kantine.pauzes') as $naam => [$van, $tot]) {
            $overzicht[$naam] = Tijdslot::tussen($van, $tot)
                ->with(['bestellingen.user', 'bestellingen.regels.product'])
                ->orderBy('start')
                ->get();
        }

        return view('beheer.index', ['overzicht' => $overzicht]);
    }

    public function status(Request $request, Bestelling $bestelling)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['open', 'klaar', 'afgehaald'])],
        ]);

        $bestelling->update(['status' => $data['status']]);

        return back()->with('melding', "Bestelling #{$bestelling->id} staat nu op '{$data['status']}'.");
    }
}
