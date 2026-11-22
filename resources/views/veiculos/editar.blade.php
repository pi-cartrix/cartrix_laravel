@extends('layouts.main')

@section('content')
    <div class="page-head">
        <h1>Editar veículo</h1>
        <span class="spacer"></span>
        <a href="{{ route('veiculos.index') }}" class="btn btn-light">Voltar</a>
    </div>

    <form method="POST" action="{{ route('veiculos.update', (string) $vehicle->_id) }}" class="form-grid">
        @csrf

        <div class="field">
            <label>Marca</label>
            <select disabled>
                @foreach ($marcas as $marca)
                    <option value="{{ $marca }}" {{ (string) $vehicle->marca === $marca ? 'selected' : '' }}>{{ $marca }}</option>
                @endforeach
            </select>
        </div>

        <div class="field">
            <label>Chassi</label>
            <input type="text" value="{{ $vehicle->chassi }}" disabled>
        </div>

        <div class="field">
            <label>Modelo *</label>
            <input type="text" name="modelo" value="{{ old('modelo', (string) $vehicle->modelo) }}" required>
        </div>

        <div class="field">
            <label>Placa *</label>
            <input type="text" name="placa" id="placa" value="{{ old('placa', (string) $vehicle->placa) }}" required>
        </div>

        <div class="field">
            <label>Quilometragem</label>
            <input type="number" name="quilometragem" min="0" step="1" value="{{ old('quilometragem', (string) $vehicle->quilometragem) }}">
        </div>

        <div class="field">
            <label>Status</label>
            <select name="status">
                @foreach ($statuses as $key => $label)
                    <option value="{{ $key }}" {{ ($vehicle->status ?? 'disponivel') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="field field-wide">
            <button type="submit" class="btn btn-primary">Salvar alterações</button>
        </div>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('placa');
        input.addEventListener('input', function () {
            input.value = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 7);
        });
    });
    </script>
@endsection
