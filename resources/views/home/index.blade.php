@extends('layouts.main')

@section('content')
    <div class="page-head">
        <h1>Frota de veículos</h1>
    </div>

    @include('partials.listagem_veiculos', ['filtersUrl' => '/'])
@endsection
