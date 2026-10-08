@extends('layout', ['titel' => 'Mijn bestellingen'])

@section('inhoud')
    <h1>Mijn bestellingen</h1>

    @forelse ($bestellingen as $bestelling)
        <table>
            <tr>
                <th>#{{ $bestelling->id }} · afhalen {{ $bestelling->tijdslot->label() }}</th>
                <th class="bedrag">
                    <span class="status status-{{ $bestelling->status }}">{{ $bestelling->status }}</span>
                </th>
            </tr>
            @foreach ($bestelling->regels as $regel)
                <tr>
                    <td>{{ $regel->aantal }} × {{ $regel->product->naam }}</td>
                    <td class="bedrag">{{ euro($regel->regelTotaal()) }}</td>
                </tr>
            @endforeach
            @if ($bestelling->korting > 0)
                <tr><td>Personeelskorting</td><td class="bedrag">− {{ euro($bestelling->korting) }}</td></tr>
            @endif
            <tr>
                <td>
                    <strong>Totaal</strong>
                    @if ($bestelling->isOpen())
                        · <a href="{{ route('bestellingen.edit', $bestelling) }}">aanpassen</a>
                    @endif
                </td>
                <td class="bedrag"><strong>{{ euro($bestelling->totaal) }}</strong></td>
            </tr>
        </table>
    @empty
        <p>Je hebt nog niets besteld. <a href="{{ route('bestellen') }}">Nu bestellen</a></p>
    @endforelse
@endsection
