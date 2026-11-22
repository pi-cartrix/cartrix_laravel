@extends('layouts.main')

@section('content')
    <div class="page-head">
        <h1>{{ $vehicle->marca }} {{ $vehicle->modelo }}</h1>
        <span class="spacer"></span>
        <a href="{{ route('veiculos.edit', (string) $vehicle->_id) }}" class="btn btn-primary">Editar</a>
        <a href="{{ route('revisoes.create', ['vehicle_id' => (string) $vehicle->_id]) }}" class="btn btn-light">Registrar revisão</a>
        <a href="{{ route('danos.create', ['vehicle_id' => (string) $vehicle->_id]) }}" class="btn btn-light">Registrar dano</a>
    </div>

    <div class="card-grid">
        <div class="card">
            <h3>Dados do veículo</h3>
            <dl class="detail">
                <dt>Placa</dt><dd>{{ $vehicle->placa }}</dd>
                <dt>Chassi</dt><dd>{{ $vehicle->chassi }}</dd>
                <dt>Quilometragem</dt><dd>{{ number_format((int) $vehicle->quilometragem, 0, ',', '.') }} km</dd>
                <dt>Status</dt><dd>@include('partials.badge_status', ['status' => (string) $vehicle->status])</dd>
            </dl>
        </div>

        <div class="card">
            <h3>Atualizar status</h3>
            <form method="POST" action="{{ route('veiculos.status', (string) $vehicle->_id) }}" class="inline-form">
                @csrf
                <select name="status">
                    @foreach ($statuses as $key => $label)
                        <option value="{{ $key }}" {{ ($vehicle->status ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Alterar</button>
            </form>
        </div>
    </div>

    @if (isset($danoLocalizacoes) && ! empty($vehicle->danos))
        <div class="card">
            <h3>Danos registrados</h3>
            @if (is_array($vehicle->danos) && count($vehicle->danos) > 0)
                <ul>
                    @foreach ($vehicle->danos as $dano)
                        <li>{{ $dano['descricao'] ?? '' }}</li>
                    @endforeach
                </ul>
            @else
                <p class="muted">Nenhum dano registrado.</p>
            @endif
        </div>
    @endif

    @if (! empty($damages) && $damages->isNotEmpty())
        <div class="card">
            <h3>Últimos danos</h3>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr><th>Data</th><th>Gravidade</th><th>Descrição</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($damages as $dano)
                            <tr>
                                <td>{{ $dano->data?->format('d/m/Y') ?? '—' }}</td>
                                <td><span class="badge badge-{{ $dano->gravidade }}">{{ \App\Models\Dano::GRAVIDADES[$dano->gravidade] ?? $dano->gravidade }}</span></td>
                                <td>{{ $dano->descricao }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <p class="muted">Nenhum dano registrado no histórico.</p>
    @endif
@endsection
