<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fuvarozó rendszer | @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        html {
            min-height: 70vh;
            margin: 0;
            background-image: url(@yield('image'));
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>

<body>
    <header>
        <h1 class="mt-3 mx-3">Fuvarozó rendszer</h1>
        <nav class="navbar navbar-expand-lg bg-body-tertiary mb-3">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/">Főoldal</a>
                    </li>
                    @auth
                        @if ($admin)
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page"
                                    href="{{ route('vehicles.index') }}">Járművek</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="{{ route('jobs.create') }}">Új munka</a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item me-2">
                            <a class="btn btn-outline-success" aria-current="page"
                                href="{{ route('login') }}">Bejelentkezés</a>
                        </li>
                        <li class="nav-item me-2">
                            <a class="btn btn-outline-success" aria-current="page"
                                href="{{ route('register') }}">Regisztráció</a>
                        </li>
                    @endguest

                    @auth
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-success">Kijelentkezés</button>
                            </form>
                        </li>
                    @endauth

                </ul>
            </div>
        </nav>
    </header>
    <main>
        @yield('content')
    </main>
    <footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
    </footer>
</body>

</html>
