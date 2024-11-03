@extends('layouts.layout')

@section('title', 'Járművek')

@push('buttons')
    <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="{{ route('vehicles.create') }}">Új jármű</a>
    </li>
@endpush

@section('content')

    @auth
        <div class="container">
            @foreach ($vehicles as $vehicle)
                <div class="row my-5 shadow p-3 py-4">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-2">
                                @if (is_null($vehicle->user_id))
                                    <span class="badge fs-6 rounded-pill text-bg-success">Szabad</span>
                                @else
                                    <span class="badge fs-6 rounded-pill text-bg-danger">Használatban</span>
                                @endif
                            </div>
                            <div class="col-2">
                                <span class="fw-bold fs-5">{{ $vehicle->registration }}</span>
                            </div>
                            <div class="col-4">
                                {{ $vehicle->brand }} - {{ $vehicle->type }}
                            </div>
                            <div class="col-2 d-grid">
                                <a href={{ route('vehicles.edit', ['vehicle' => $vehicle->id]) }}
                                    class="btn btn-outline-dark my-auto">Szerkesztés</a>
                            </div>
                            <div class="col-2">
                                <form class="d-grid" action="{{ route('vehicles.destroy', ['vehicle' => $vehicle->id]) }}"
                                    method="post">
                                    @csrf
                                    @method('delete')
                                    <input type="submit" value="Törlés" class="btn btn-outline-danger my-auto">
                                </form>
                            </div>
                        </div>
                        @if (!is_null($vehicle->user_id))
                            <div class="row">
                                <div class="col"><span class="fs-5">Jelenlegi sofőr: {{ $vehicle->user->name }}</span>
                                </div>
                            </div>
                        @elseif (count($free_users) > 0)
                            <div class="row mt-2">
                                <div>
                                    <form action={{ route('vehicles.allocate', ['id' => $vehicle->id]) }} method="post">
                                        @csrf
                                        @method('patch')
                                        <div class="row">
                                            <div class="col-8">
                                                <select class="form-select form-select-lg" name="user_id" id="user_id">
                                                    @foreach ($free_users as $user)
                                                        <option value={{ $user->id }}>{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <input class="btn btn-primary btn-lg" type="submit" value="Foglalás">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endauth


@endsection
