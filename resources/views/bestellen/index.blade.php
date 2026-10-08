@extends('layout', ['titel' => 'Bestellen'])

@section('inhoud')
    <h1>Bestellen</h1>

    <form method="POST" action="{{ route('bestellen.store') }}">
        @csrf

        @foreach ($categorieen as $sleutel => $kopje)
            @if ($producten->has($sleutel))
                <h2>{{ $kopje }}</h2>
                <table>
                    @foreach ($producten[$sleutel] as $product)
                        <tr>
                            <td>{{ $product->naam }}</td>
                            <td class="bedrag">{{ euro($product->prijs) }}</td>
                            <td style="width:6rem">
                                <input type="number" name="aantal[{{ $product->id }}]" min="0" max="10"
                                       value="{{ old('aantal.'.$product->id, 0) }}" aria-label="Aantal {{ $product->naam }}">
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif
        @endforeach

        <label>Afhaaltijd
            <select name="tijdslot_id">
                @foreach ($tijdsloten as $tijdslot)
                    <option value="{{ $tijdslot->id }}" @selected(old('tijdslot_id') == $tijdslot->id)>{{ $tijdslot->label() }}</option>
                @endforeach
            </select>
        </label>

        <label>Opmerking (optioneel)
            <input type="text" name="opmerking" value="{{ old('opmerking') }}" maxlength="255" size="40">
        </label>

        @if (auth()->user()->krijgtPersoneelskorting())
            <p class="hint">Je krijgt 15% personeelskorting.</p>
        @endif

        <button>Bestelling plaatsen</button>
    </form>
@endsection
