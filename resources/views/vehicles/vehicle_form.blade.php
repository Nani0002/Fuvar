@extends('layouts.layout')

@section('title', isset($vehicle) ? 'Jármű szerkesztése' : 'Jármű létrehozása')

@push('buttons')
    <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="{{ route('vehicles.create') }}">Új jármű</a>
    </li>
@endpush

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-8 offset-2 mt-5 shadow p-5 rounded-4">
                <div class="fs-4 fw-bold">{{ isset($vehicle) ? 'Jármű szerkesztése' : 'Jármű létrehozása' }}</div>
                <form
                    action={{ isset($vehicle) ? route('vehicles.update', ['vehicle' => $vehicle->id]) : route('vehicles.store') }}
                    method="post">
                    @csrf
                    @isset($vehicle)
                        @method('patch')
                    @endisset
                    <div class="my-3 form-floating">
                        <input type="text" name="brand" id="brand"
                            value="{{ old('brand', $vehicle->brand ?? '') }}" class="form-control" placeholder="Márka">
                        <label for="addressee_name">Márka</label>
                    </div>

                    <div class="my-3 form-floating">
                        <input type="text" name="type" id="type" value="{{ old('type', $vehicle->type ?? '') }}"
                            class="form-control" placeholder="Típus">
                        <label for="type">Típus</label>
                    </div>

                    <div class="my-3 form-floating">
                        <input type="text" name="registration" id="registration" value="{{ old('registration', $vehicle->registration ?? '') }}"
                            class="form-control" placeholder="Rendszám">
                        <label for="registration">Rendszám</label>
                    </div>

                    @if (isset($vehicle) && !is_null($vehicle->user_id))
                        <div class="form-floating">
                            <select class="form-select" name="user_id" id="user_id" disabled>
                                <option value={{ $vehicle->user_id }}>{{ $vehicle->user->name }}</option>
                            </select>
                            <label for="user_id" class="mb-2">Fuvarozó munkatárs</label>
                        </div>
                    @else
                        <div class="form-floating">
                            <select class="form-select" name="user_id" id="user_id">
                                <option value="-1">Későbbi kitűzés</option>
                                @foreach ($free_users as $user)
                                    <option value={{ $user->id }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <label for="user_id" class="mb-2">Fuvarozó munkatárs</label>
                        </div>
                    @endif


                    <div class="row mt-5">
                        <div class="col-10 offset-1 d-grid">
                            <input class="btn btn-success" type="submit"
                                value={{ isset($vehicle) ? 'Szerkezsztés' : 'Létrehozás' }}>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    @endsection
