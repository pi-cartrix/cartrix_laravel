<form method="GET" action="{{ url($filtersUrl ?? '/veiculos') }}" class="filters">
    <input type="text" name="q" value="{{ $query }}" placeholder="Buscar por placa, marca, modelo ou chassi...">

    <select name="status">
        <option value="">Todos os status</option>
        @foreach ($statuses as $key => $label)
            <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>

    <button type="submit" class="btn btn-primary">Buscar</button>
    @if ($query !== '' || $status !== '')
        <a href="{{ url($filtersUrl ?? '/veiculos') }}" class="btn btn-light">Limpar</a>
    @endif
</form>

@if (empty($vehicles) || $vehicles->isEmpty())
    <p class="muted">Nenhum veículo encontrado.</p>
@else
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Placa</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Quilometragem</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vehicles as $vehicle)
                    <tr>
                        <td><strong>{{ $vehicle->placa }}</strong></td>
                        <td>{{ $vehicle->marca }}</td>
                        <td>{{ $vehicle->modelo }}</td>
                        <td>{{ number_format((int) $vehicle->quilometragem, 0, ',', '.') }} km</td>
                        <td>
                            @include('partials.badge_status', ['status' => (string) $vehicle->status])
                        </td>
                        <td class="actions">
                            <a href="{{ route('veiculos.show', (string) $vehicle->_id) }}" class="btn btn-sm btn-light">Ver</a>
                            <a href="{{ route('veiculos.edit', (string) $vehicle->_id) }}" class="btn btn-sm btn-light">Editar</a>
                            <a href="{{ route('revisoes.history', (string) $vehicle->_id) }}" class="btn btn-sm btn-light">Revisões</a>
                            <a href="{{ route('danos.index', (string) $vehicle->_id) }}" class="btn btn-sm btn-light">Danos</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
