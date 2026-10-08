@extends('layout', ['titel' => 'Producten'])

@section('inhoud')
    <h1>Producten</h1>

    <table>
        <tr><th>Naam</th><th>Categorie</th><th class="bedrag">Prijs</th><th>Beschikbaar</th></tr>
        @foreach ($producten as $product)
            <tr>
                <td>{{ $product->naam }}</td>
                <td>{{ $product->categorie }}</td>
                <td class="bedrag">{{ euro($product->prijs) }}</td>
                <td>{{ $product->beschikbaar ? 'ja' : 'nee' }}</td>
            </tr>
        @endforeach
    </table>

    <h2>Product toevoegen</h2>
    <form method="POST" action="{{ route('beheer.producten.store') }}">
        @csrf
        <label>Naam <input type="text" name="naam" value="{{ old('naam') }}" required maxlength="100"></label>
        <label>Prijs in euro's (bijv. 3,25) <input type="text" name="prijs" value="{{ old('prijs') }}" required size="6"></label>
        <label>Categorie
            <select name="categorie">
                @foreach ($categorieen as $sleutel => $kopje)
                    <option value="{{ $sleutel }}" @selected(old('categorie') === $sleutel)>{{ $kopje }}</option>
                @endforeach
            </select>
        </label>
        <label><input type="checkbox" name="beschikbaar" value="1" checked> Direct beschikbaar</label>
        <button>Toevoegen</button>
    </form>
@endsection
