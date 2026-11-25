<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Pi Cartrix' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="m-stripe"></div>

    <header>
        <nav>
            <a href="{{ auth()->check() ? route('veiculos.index') : url('/') }}" class="brand">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-logo" style="height: 64px; width: auto;">
            </a>
            @auth
                <span class="nav-sep"></span>
                <a href="{{ route('veiculos.index') }}">Frota</a>
                <a href="{{ route('relatorios.index') }}">Relatórios</a>
                <a href="{{ route('revisoes.create') }}">Revisões</a>
                <span class="spacer"></span>
                <span class="user">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="link-btn">Sair</button>
                </form>
            @else
                <span class="spacer"></span>
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Cadastro</a>
            @endauth
        </nav>
    </header>

    <main>
        @include('partials.flash')
        @yield('content')
    </main>

    <footer>
        @include('partials.footer')
    </footer>
</body>
</html>
