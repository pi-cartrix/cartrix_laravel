<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use MongoDB\BSON\ObjectId;
use MongoDB\Laravel\Eloquent\Casts\ObjectId as ObjectIdCast;
use MongoDB\Laravel\Eloquent\Model;

class Dano extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'danos';

    protected $fillable = [
        'vehicle_id',
        'descricao',
        'data',
        'gravidade',
        'localizacao',
        'observacoes',
    ];

    protected $casts = [
        'vehicle_id' => ObjectIdCast::class,
        'data' => 'datetime',
    ];

    public const GRAVIDADES = [
        'baixa' => 'Baixa',
        'media' => 'Média',
        'alta' => 'Alta',
        'critica' => 'Crítica',
    ];

    public const LOCALIZACOES = [
        'dianteira' => 'Dianteira',
        'traseira' => 'Traseira',
        'lateral_esquerda' => 'Lateral esquerda',
        'lateral_direita' => 'Lateral direita',
        'teto' => 'Teto',
        'interior' => 'Interior',
        'outro' => 'Outro',
    ];

    public static function localizacoes(): array
    {
        return self::LOCALIZACOES;
    }

    public static function porVeiculo(ObjectId|string $vehicleId): Collection
    {
        $id = $vehicleId instanceof ObjectId ? $vehicleId : new ObjectId($vehicleId);

        return static::where('vehicle_id', $id)
            ->orderByDesc('data')
            ->get();
    }
}
