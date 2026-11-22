<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use MongoDB\Laravel\Eloquent\Model;

class Veiculo extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'veiculos';

    protected $fillable = [
        'marca',
        'modelo',
        'placa',
        'chassi',
        'quilometragem',
        'status',
        'danos',
    ];

    protected $casts = [
        'quilometragem' => 'integer',
        'danos' => 'array',
    ];

    public const STATUSES = [
        'disponivel' => 'Disponível',
        'em_revisao' => 'Em revisão',
        'danificado' => 'Danificado',
        'em_manutencao' => 'Em manutenção',
        'indisponivel' => 'Indisponível',
    ];

    public static function findByPlate(string $plate): ?self
    {
        return static::where('placa', strtoupper(trim($plate)))->first();
    }

    public static function search(?string $query = null, ?string $status = null): Collection
    {
        $builder = static::query();

        if ($status !== null && $status !== '' && isset(self::STATUSES[$status])) {
            $builder->where('status', $status);
        }

        if ($query !== null && trim($query) !== '') {
            $term = '%'.trim($query).'%';

            $builder->where(function ($builder) use ($term): void {
                $builder->where('placa', 'like', $term)
                    ->orWhere('marca', 'like', $term)
                    ->orWhere('modelo', 'like', $term)
                    ->orWhere('chassi', 'like', $term);
            });
        }

        return $builder->orderByDesc('created_at')->get();
    }

    public function atualizarVeiculo(array $data): bool
    {
        return $this->update($data);
    }

    public function atualizarStatus(string $status): bool
    {
        return $this->update(['status' => $status]);
    }
}
