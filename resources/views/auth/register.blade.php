@extends('layouts.layout')

@section('title', 'Regisztráció')

@section('content')
    <div class="container">
        <div class="row mt-5">
            <div class="col-6 offset-3 shadow p-5 py-4 rounded-4">
                <div class="row mb-4 fs-5 fw-bold">Regisztráció</div>
                <div class="row">
                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        @error('name')
                            <span class="text-danger fw-light">{{ $errors->get('name')[0] }}</span>
                        @enderror
                        <div class="mb-3 form-floating">
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                class="form-control" placeholder="Teljes név">
                            <label for="name">Teljes név</label>
                        </div>

                        @error('email')
                            <span class="text-danger fw-light">{{ $errors->get('email')[0] }}</span>
                        @enderror
                        <div class="mb-3 form-floating">
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="form-control" placeholder="Email cím">
                            <label for="email">Email cím</label>
                        </div>

                        @error('password')
                            <span class="text-danger fw-light">{{ $errors->get('password')[0] }}</span>
                        @enderror
                        <div class="mb-3 form-floating">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Jelszó">
                            <label for="password">Jelszó</label>
                        </div>

                        @error('password_confirmation')
                            <span class="text-danger fw-light">{{ $errors->get('password_confirmation')[0] }}</span>
                        @enderror
                        <div class="my-3 form-floating">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" placeholder="Jelszó mégegyszer">
                            <label for="password_confirmation">Jelszó mégegyszer</label>
                        </div>

                        <div class="row mt-4">
                            <div class="col-4 offset-4 d-grid">
                                <a href="{{ route('index') }}" class="btn btn-success my-auto">Már regisztrált?</a>
                            </div>
                            <div class="col-3 d-grid">
                                <input class="btn btn-success" type="submit" value="Regisztráció">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
