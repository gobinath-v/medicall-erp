@extends('layouts.admin')

@section('content')

<div class="container mt-3">
    {{-- <span>Visitors dashboard</span> --}}
    @livewire('event-form-summary')
</div>

@endsection
