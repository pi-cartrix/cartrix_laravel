<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use MongoDB\BSON\ObjectId;
use MongoDB\Laravel\Eloquent\Casts\ObjectId as ObjectIdCast;
use MongoDB\Laravel\Eloquent\Model;

class Revisao extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'revisoes';

    protected $fillable = [
        'vehicle_id',
        'data_revisao',
        'quilometragem',
        'tipo',
        'descricao',
        'pecas_substituidas',
        'valor',
        'responsavel',
    ];

    protected $casts = [
        'vehicle_id' => ObjectIdCast::class,
        'data_revisao' => 'datetime',
        'quilometragem' => 'integer',
        'valor' => 'float',
    ];

    public const TIPOS = [
        'preventiva' => 'Preventiva',
        'corretiva' => 'Corretiva',
        'revisao_programada' => 'Revisão programada',
        'troca_pneus' => 'Troca de pneus',
        'troca_oleo' => 'Troca de óleo',
        'outra' => 'Outra',
    ];

    public static function porVeiculo(ObjectId|string $vehicleId): Collection
    {
        $id = $vehicleId instanceof ObjectId ? $vehicleId : new ObjectId($vehicleId);

        return static::where('vehicle_id', $id)
            ->orderByDesc('data_revisao')
            ->get();
    }
}
