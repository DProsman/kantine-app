@extends('layout', ['titel' => 'Beheer'])

@section('inhoud')
    <h1>Bestellingen vandaag</h1>

    @foreach ($overzicht as $pauze => $tijdsloten)
        <h2>{{ $pauze }}
            <span class="hint">· {{ $tijdsloten->sum(fn ($t) => $t->bestellingen->count()) }} bestellingen,
                omzet {{ euro($tijdsloten->sum(fn ($t) => $t->bestellingen->sum('totaal'))) }}</span>
        </h2>

        @forelse ($tijdsloten as $tijdslot)
            <table>
                <tr>
                    <th colspan="3">{{ $tijdslot->label() }}</th>
                    <th class="bedrag">{{ $tijdslot->bestellingen->count() }} / {{ $tijdslot->capaciteit }}</th>
                    <th></th>
                </tr>
                @forelse ($tijdslot->bestellingen as $bestelling)
                    <tr>
                        <td>#{{ $bestelling->id }} {{ $bestelling->user->name }}</td>
                        <td>
                            @foreach ($bestelling->regels as $regel)
                                {{ $regel->aantal }}× {{ $regel->product->naam }}@if (! $loop->last), @endif
                            @endforeach
                            @if ($bestelling->opmerking)
                                <br><em class="hint">{{ $bestelling->opmerking }}</em>
                            @endif
                        </td>
                        <td><span class="status status-{{ $bestelling->status }}">{{ $bestelling->status }}</span></td>
                        <td class="bedrag">{{ euro($bestelling->totaal) }}</td>
                            <td>
                                @foreach (['klaar', 'afgehaald'] as $nieuweStatus)
                                    @if ($bestelling->status !== $nieuweStatus)
                                        <form method="POST" action="{{ route('beheer.status', $bestelling) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $nieuweStatus }}">
                                            <button class="klein">{{ $nieuweStatus }}</button>
                                        </form>
                                    @endif
                                @endforeach
                            </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="hint">Nog geen bestellingen.</td></tr>
                @endforelse
            </table>
        @empty
            <p class="hint">Geen tijdsloten in deze pauze.</p>
        @endforelse
    @endforeach
@endsection
