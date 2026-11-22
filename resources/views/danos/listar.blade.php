@extends('layouts.main')

@section('content')
    <div class="page-head">
        <h1>Danos do veículo</h1>
        <span class="spacer"></span>
        <a href="{{ route('veiculos.show', (string) $vehicle->_id) }}" class="btn btn-light">Voltar</a>
        <a href="{{ route('danos.create', ['vehicle_id' => (string) $vehicle->_id]) }}" class="btn btn-primary">Registrar dano</a>
    </div>

    <div class="card">
        <h3>
            {{ $vehicle->marca }} {{ $vehicle->modelo }}
            <span class="muted">— placa {{ $vehicle->placa }}</span>
        </h3>
        <p>Status atual:
            @include('partials.badge_status', ['status' => (string) $vehicle->status])
        </p>
    </div>

    @if ($damages->isEmpty())
        <div class="empty-state">
            <p>Nenhum dano registrado para este veículo.</p>
            <a href="{{ route('danos.create', ['vehicle_id' => (string) $vehicle->_id]) }}" class="btn btn-primary">Registrar primeiro dano</a>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Gravidade</th>
                        <th>Localização</th>
                        <th>Descrição</th>
                        <th>Observações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($damages as $dano)
                        <tr>
                            <td>{{ $dano->data?->format('d/m/Y') ?? '—' }}</td>
                            <td><span class="badge badge-{{ $dano->gravidade }}">{{ \App\Models\Dano::GRAVIDADES[$dano->gravidade] ?? $dano->gravidade }}</span></td>
                            <td>{{ \App\Models\Dano::LOCALIZACOES[$dano->localizacao] ?? $dano->localizacao }}</td>
                            <td>{{ $dano->descricao }}</td>
                            <td>{{ $dano->observacoes }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
