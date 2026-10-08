@extends('layout', ['titel' => 'Inloggen'])

@section('inhoud')
    <h1>Inloggen</h1>
    <p class="hint">Demo-omgeving: kies met welke gebruiker je wilt inloggen.</p>

    <form method="POST" action="{{ url('/login') }}">
        @csrf
        <table>
            <tr><th></th><th>Naam</th><th>Rol</th></tr>
            @foreach ($gebruikers as $gebruiker)
                <tr>
                    <td><input type="radio" name="user_id" value="{{ $gebruiker->id }}" id="u{{ $gebruiker->id }}" @checked($loop->first)></td>
                    <td><label for="u{{ $gebruiker->id }}" style="margin:0">{{ $gebruiker->name }}</label></td>
                    <td>{{ $gebruiker->rol }}{{ $gebruiker->werkt_bij_kantine ? ' (werkt in kantine)' : '' }}</td>
                </tr>
            @endforeach
        </table>
        <button>Inloggen</button>
    </form>
@endsection
