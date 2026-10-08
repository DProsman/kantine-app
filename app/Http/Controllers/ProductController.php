<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        return view('beheer.producten', [
            'producten' => Product::orderBy('categorie')->orderBy('naam')->get(),
            'categorieen' => config('kantine.categorieen'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'naam' => ['required', 'string', 'max:100'],
            'prijs' => ['required', 'regex:/^\d{1,3}([,.]\d{1,2})?$/'],
            'categorie' => ['required', Rule::in(array_keys(config('kantine.categorieen')))],
        ]);

        Product::create([
            'naam' => $data['naam'],
            'prijs' => (int) round((float) str_replace(',', '.', $data['prijs']) * 100),
            'categorie' => $data['categorie'],
            'beschikbaar' => $request->boolean('beschikbaar'),
        ]);

        return back()->with('melding', "{$data['naam']} is toegevoegd.");
    }
}
