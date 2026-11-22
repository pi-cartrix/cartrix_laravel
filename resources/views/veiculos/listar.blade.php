@extends('layouts.main')

@section('content')
    <div class="page-head">
        <h1>Frota de veículos</h1>
        <span class="spacer"></span>
        <a href="{{ route('veiculos.create') }}" class="btn btn-primary">Cadastrar veículo</a>
    </div>

    @if (! empty($statusCounts))
        <div class="status-summary">
            @foreach ($statuses as $key => $label)
                <div class="stat-card">
                    <span class="badge badge-{{ $key }}">{{ $label }}</span>
                    <strong>{{ (int) ($statusCounts[$key] ?? 0) }}</strong>
                </div>
            @endforeach
        </div>
    @endif

    @include('partials.listagem_veiculos', ['filtersUrl' => '/veiculos'])
@endsection
