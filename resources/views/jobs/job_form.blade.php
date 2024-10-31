@extends('layouts.layout')

@section('title', isset($job) ? 'Munka szerkesztése' : 'Munka létrehozása')

@section('content')


    <div class="container">
        <div class="row">
            <div class="col-8 offset-2 mt-5 shadow p-5 rounded-4">
                <div class="fs-4 fw-bold">{{ isset($job) ? 'Munka szerkesztése' : 'Munka létrehozása' }}</div>
                <form action={{ isset($job) ? route('jobs.update', ['job' => $job->id]) : route('jobs.store') }}
                    method="post">
                    @csrf
                    @isset($job)
                        @method('patch')
                    @endisset
                    <div class="my-3 form-floating">
                        <input type="text" name="addressee_name" id="addressee_name"
                            value="{{ old('addressee_name', $job->addressee_name ?? '') }}" class="form-control"
                            placeholder="Címzett neve">
                        <label for="addressee_name">Címzett neve</label>
                    </div>

                    <div class="my-3 form-floating">
                        <input type="text" name="addressee_phone" id="addressee_phone"
                            value="{{ old('addressee_phone', $job->addressee_phone ?? '') }}" class="form-control"
                            placeholder="Címzett neve">
                        <label for="addressee_phone">Címzett telefonszáma</label>
                    </div>

                    <div class="my-3 form-floating">
                        <input type="text" name="start_address" id="start_address"
                            value="{{ old('start_address', $job->start_address ?? '') }}" class="form-control"
                            placeholder="Kiindulási cím">
                        <label for="start_address">Kiindulási cím</label>
                    </div>

                    <div class="my-3 form-floating">
                        <input type="text" name="end_address" id="end_address"
                            value="{{ old('end_address', $job->end_address ?? '') }}" class="form-control"
                            placeholder="Érkezési cím">
                        <label for="end_address">Érkezési cím</label>
                    </div>

                    @if (isset($job) && $job->status != 0)
                        <div class="form-floating">
                            <select class="form-select" name="user_id" id="user_id" disabled>
                                <option value={{ $job->user_id }}>{{ $job->user->name }}</option>
                            </select>
                            <label for="user_id" class="mb-2">Fuvarozó munkatárs</label>
                        </div>
                    @else
                        <div class="form-floating">
                            <select class="form-select" name="user_id" id="user_id">
                                <option value="-1">Későbbi kitűzés</option>
                                @foreach ($users as $user)
                                <option value={{ $user->id }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <label for="user_id" class="mb-2">Fuvarozó munkatárs</label>
                        </div>
                    @endif


                    <div class="row mt-5">
                        <div class="col-10 offset-1 d-grid">
                            <input class="btn btn-success" type="submit"
                                value={{ isset($job) ? 'Szerkezsztés' : 'Létrehozás' }}>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>

@endsection
