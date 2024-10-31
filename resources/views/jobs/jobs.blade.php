@extends('layouts.layout')

@section('title', 'Munkák')

@section('content')


    @foreach ($jobs as $job)
        {{ $job->addressee_name }}
    @endforeach

@endsection
