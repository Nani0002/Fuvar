<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fuvarozó rendszer | @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body data-bs-theme="dark">
    <header>
        <h1 class="mt-3 mx-3">Fuvarozó rendszer</h1>
        <nav class="navbar navbar-expand-lg bg-body-tertiary mb-3">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active fw-bold" aria-current="page" href="/">Főoldal</a>
                    </li>
                    @auth
                        @if ($admin)
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page"
                                    href="{{ route('vehicles.index') }}">Járművek</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="{{ route('jobs.create') }}">Új
                                    munka</a>
                            </li>
                        @endif
                    @endauth
                    @stack('buttons')
                </ul>

                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item me-2">
                            <a class="btn btn-outline-success" aria-current="page"
                                href="{{ route('register') }}">Regisztráció</a>
                        </li>
                    @endguest

                    @auth
                        @if ($admin && isset($undiscardeds) && count($undiscardeds) > 0)
                            <li class="nav-item me-4"><button class="btn btn-outline-danger position-relative read-messages"
                                    data-bs-toggle="modal" data-bs-target="#undismissedModal"
                                    id="read-messages-btn">Sikertelen kézbesítések
                                    @if ($counter > 0)
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                            id="message-counter">{{ $counter }}</span>
                                </button>
                        @endif
                        </li>
                        <div class="modal fade" id="undismissedModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Sikertelen kézbesítések</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @foreach ($undiscardeds as $undiscarded)
                                            <div class="message-div row mb-2">
                                                <div class="col-11">
                                                    <div class="row">
                                                        <div class="col-6">{{ $undiscarded->user->name }}</div>
                                                        <div class="col-6">- {{ $undiscarded->addressee_name }}</div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">{{ $undiscarded->message }}</div>
                                                    </div>
                                                </div>

                                                <div class="col-1"><button type="button" class="btn-close dismiss-message mt-2"
                                                        data-id="{{ $undiscarded->id }}"></button></div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @push('scripts')
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script>
                                window.Laravel = {
                                    csrfToken: '{{ csrf_token() }}'
                                }
                            </script>
                            <script src="{{ asset('js/dismissmessage.js') }}"></script>
                        @endpush
                        @endif
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
        @stack('scripts')
    </footer>
</body>

</html>
