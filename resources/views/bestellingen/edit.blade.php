@extends('layout', ['titel' => 'Bestelling aanpassen'])

@section('inhoud')
    <h1>Bestelling #{{ $bestelling->id }} aanpassen</h1>
    <p class="hint">Afhalen: {{ $bestelling->tijdslot->label() }}. Zet een aantal op 0 om een product te verwijderen.</p>

    <form method="POST" action="{{ route('bestellingen.update', $bestelling) }}">
        @csrf
        @method('PUT')

        <table>
            @foreach ($bestelling->regels as $regel)
                <tr>
                    <td>{{ $regel->product->naam }}</td>
                    <td class="bedrag">{{ euro($regel->stukprijs) }}</td>
                    <td style="width:6rem">
                        <input type="number" name="aantal[{{ $regel->id }}]" min="0" max="10"
                               value="{{ old('aantal.'.$regel->id, $regel->aantal) }}">
                    </td>
                </tr>
            @endforeach
        </table>

        <label>Opmerking
            <input type="text" name="opmerking" value="{{ old('opmerking', $bestelling->opmerking) }}" maxlength="255" size="40">
        </label>

        <button>Opslaan</button>
        <a href="{{ route('bestellingen.index') }}" style="margin-left:1rem">Annuleren</a>
    </form>
@endsection
