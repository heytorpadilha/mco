<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>

    @vite(['resources/css/app.css'])
    <title>Meu cardápio Online</title>
</head>

<body>
    <div class="main-container">
        <header class="header">
            <div class="content-header">
                <h2 class="title-logo">
                    <a href="{{ route('dashboard') }}">MCO</a>
                </h2>
                <ul class="list-nav-link">
                    <li>
                        <a href="{{ route('user.index') }}" class="nav-link">Usuários</a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard') }}" class="nav-link">Sair</a>
                    </li>
                </ul>
            </div>

        </header>
        @yield('content')
    </div>
</body>

</html>
