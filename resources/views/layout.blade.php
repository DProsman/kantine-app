<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $titel ?? 'Kantine' }} · Kantine-app</title>
    <style>
        :root { --groen: #2f5d50; --licht: #f6f4ef; --rand: #ddd6c8; }
        * { box-sizing: border-box; }
        body { font-family: system-ui, -apple-system, "Segoe UI", sans-serif; margin: 0; background: var(--licht); color: #222; line-height: 1.45; }
        header { background: var(--groen); color: #fff; padding: .8rem 1.5rem; display: flex; flex-wrap: wrap; gap: .5rem; justify-content: space-between; align-items: center; }
        header nav { display: flex; flex-wrap: wrap; align-items: center; gap: 1rem; }
        header a, header button { color: #fff; }
        main { max-width: 960px; margin: 1.5rem auto; padding: 0 1rem; }
        h1 { margin-top: 0; }
        table { width: 100%; border-collapse: collapse; background: #fff; margin-bottom: 1.2rem; border: 1px solid var(--rand); }
        th, td { text-align: left; padding: .5rem .7rem; border-bottom: 1px solid var(--rand); vertical-align: top; }
        th { background: #efeadf; font-weight: 600; }
        td.bedrag, th.bedrag { text-align: right; white-space: nowrap; }
        input, select { font: inherit; padding: .3rem .4rem; }
        input[type=number] { width: 4.5rem; }
        label { display: block; margin: .8rem 0; }
        button { font: inherit; padding: .4rem .9rem; background: var(--groen); color: #fff; border: 0; border-radius: 4px; cursor: pointer; }
        button.link { background: none; padding: 0; text-decoration: underline; }
        button.klein { padding: .2rem .5rem; font-size: .9em; }
        .melding { background: #dff0d8; padding: .6rem 1rem; margin-bottom: 1rem; border-radius: 4px; }
        .fout { background: #f8d7da; padding: .6rem 1rem; margin-bottom: 1rem; border-radius: 4px; }
        .hint { color: #555; font-size: .95em; }
        .status { display: inline-block; padding: .1rem .5rem; border-radius: 99px; background: #eee; font-size: .85em; }
        .status-klaar { background: #fff3cd; }
        .status-afgehaald { background: #d1e7dd; }
        form.inline { display: inline; }
    </style>
</head>
<body>
<header>
    <strong>Kantine-app</strong>
    @auth
        <nav>
            <a href="{{ route('bestellen') }}">Bestellen</a>
            @if (Route::has('bestellingen.index'))
                <a href="{{ route('bestellingen.index') }}">Mijn bestellingen</a>
            @endif
            @if (auth()->user()->isBeheerder() && Route::has('beheer.index'))
                <a href="{{ route('beheer.index') }}">Beheer</a>
                @if (Route::has('beheer.producten'))
                    <a href="{{ route('beheer.producten') }}">Producten</a>
                @endif
            @endif
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button class="link">Uitloggen ({{ auth()->user()->name }})</button>
            </form>
        </nav>
    @endauth
</header>
<main>
    @if (session('melding'))
        <div class="melding">{{ session('melding') }}</div>
    @endif
    @if ($errors->any())
        <div class="fout">
            <ul>
                @foreach ($errors->all() as $fout)
                    <li>{{ $fout }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('inhoud')
</main>
</body>
</html>
