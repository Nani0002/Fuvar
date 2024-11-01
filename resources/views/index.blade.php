@extends('layouts.layout')

@section('title', 'Főoldal')

@section('content')

    @auth
        <div class="container">
            <form action="/jobs/status" method="GET">
                <div class="row">
                    <div class="col-3 offset-7">
                        <select name="status" id="status" class="form-select form-select-lg status-modifier">
                            @if ($admin)
                                <option value="0">Nincs kiosztva</option>
                            @endif
                            <option value="1">Kiosztva</option>
                            <option value="2">Folyamatban</option>
                            <option value="3">Elvégezve</option>
                            <option value="4">Sikertelen</option>
                        </select>
                    </div>
                    <div class="col-1 d-grid">
                        <input type="submit" value="Szűrés" class="btn btn-outline-dark">
                    </div>
                    <div class="col-1 d-grid">
                        <a href="{{ route('index') }}"
                            class="btn btn-outline-danger d-flex justify-content-center align-items-center">X</a>
                    </div>
                </div>
            </form>
            @foreach ($jobs as $job)
                <div class="row my-5">
                    <div class="col offet-2 shadow p-3 py-4">
                        <div class="row mb-2 pb-2 border-bottom">
                            <div class="col-8 fw-bold">{{ $job->start_address }} ⇨ {{ $job->end_address }}</div>
                            <div class="col-2">{{ $job->addressee_name }}</div>
                            <div class="col-2">{{ $job->addressee_phone }}</div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                @switch($job->status)
                                    @case(0)
                                        <span class="badge fs-6 rounded-pill text-bg-warning">Nincs kiosztva</span>
                                    @break

                                    @case(1)
                                        <span class="badge fs-6 rounded-pill text-bg-primary">Kiosztva</span>
                                    @break

                                    @case(2)
                                        <span class="badge fs-6 rounded-pill text-bg-info">Folyamatban</span>
                                    @break

                                    @case(3)
                                        <span class="badge fs-6 rounded-pill text-bg-success">Elvégezve</span>
                                    @break

                                    @case(4)
                                        <span class="badge fs-6 rounded-pill text-bg-danger">Sikertelen</span>
                                    @break
                                @endswitch
                            </div>
                            @if ($admin)
                                <div class="col-6">
                                    @if ($job->user)
                                        Fuvarozó: {{ $job->user->name }} - {{ $job->user->vehicle->registration }}
                                    @else
                                        <form action={{ route('jobs.allocate', ['id' => $job->id]) }} method="post">
                                            @csrf
                                            @method('patch')
                                            <div class="row">
                                                <div class="col-8">
                                                    <select class="form-select form-select-lg" name="user_id" id="user_id">
                                                        @foreach ($users as $user)
                                                            <option value={{ $user->id }}>{{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <input class="btn btn-primary btn-lg" type="submit" value="Kiosztás">
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                                <div class="col-2 d-grid">
                                    <a href={{ route('jobs.edit', ['job' => $job->id]) }}
                                        class="btn btn-outline-dark my-auto">Szerkesztés</a>
                                </div>
                                <div class="col-2">
                                    <form class="d-grid" action="{{ route('jobs.destroy', ['job' => $job->id]) }}"
                                        method="post">
                                        @csrf
                                        @method('delete')
                                        <input type="submit" value="Törlés" class="btn btn-outline-danger my-auto">
                                    </form>
                                </div>
                            @elseif ($job->status == 1 || $job->status == 2)
                                <div class="col-7">
                                    <form action={{ route('jobs.progress', ['id' => $job->id]) }} method="post">
                                        @csrf
                                        @method('patch')
                                        <div class="row">
                                            <div class="col-8">
                                                <select class="form-select form-select-lg status-modifier" name="status"
                                                    id="status">
                                                    <option value="2">Folyamatban</option>
                                                    <option value="3">Elvégezve</option>
                                                    <option value="4">Sikertelen</option>
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <input class="btn btn-primary btn-lg" type="submit" value="Státusz frissítése">
                                            </div>
                                        </div>
                                        <div class="row visually-hidden">
                                            <div class="col-12 mt-2">
                                                <div class="form-floating">
                                                    <textarea class="form-control" placeholder="Megjegyzés" id="message" name="message"></textarea>
                                                    <label for="message">Megjegyzés</label>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            @if (count($jobs) == 0)
                <div class="row vh-70">
                    <div class="col h-100 d-flex justify-content-center align-items-center">
                        <h3>Jelenleg nincsenek kitűzött munkák!</h3>
                    </div>
                </div>
            @endif
        </div>
    @endauth
    @guest

    @endguest

@endsection

@push('scripts')
    <script src={{ asset('js/messagestatus.js') }}></script>
@endpush
