<?php

namespace App\Http\Controllers;

use App\Models\Bestelling;
use App\Services\Kassa;
use Illuminate\Http\Request;

class BestellingController extends Controller
{
    public function index(Request $request)
    {
        return view('bestellingen.index', [
            'bestellingen' => $request->user()->bestellingen()
                ->with(['tijdslot', 'regels.product'])
                ->latest()
                ->get(),
        ]);
    }

    public function edit(Request $request, Bestelling $bestelling)
    {
        $this->magBewerken($request, $bestelling);

        return view('bestellingen.edit', [
            'bestelling' => $bestelling->load('regels.product', 'tijdslot'),
        ]);
    }

    public function update(Request $request, Bestelling $bestelling, Kassa $kassa)
    {
        $this->magBewerken($request, $bestelling);

        $data = $request->validate([
            'aantal' => ['required', 'array'],
            'aantal.*' => ['required', 'integer', 'min:0', 'max:10'],
            'opmerking' => ['nullable', 'string', 'max:255'],
        ]);

        if (array_sum($data['aantal']) === 0) {
            return back()->withErrors(['aantal' => 'Annuleren kan alleen aan de balie.']);
        }

        foreach ($data['aantal'] as $regelId => $aantal) {
            $regel = $bestelling->regels()->findOrFail($regelId);
            (int) $aantal === 0 ? $regel->delete() : $regel->update(['aantal' => (int) $aantal]);
        }

        $bestelling->opmerking = $data['opmerking'] ?? null;
        $kassa->berekenTotaal($bestelling->load('regels'))->save();

        return redirect()->route('bestellingen.index')
            ->with('melding', "Bestelling #{$bestelling->id} is aangepast.");
    }

    private function magBewerken(Request $request, Bestelling $bestelling): void
    {
        abort_unless($bestelling->user_id === $request->user()->id, 403);
        abort_unless($bestelling->isOpen(), 403, 'Deze bestelling wordt al klaargemaakt.');
    }
}
