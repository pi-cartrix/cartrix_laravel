<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use MongoDB\Laravel\Eloquent\Model;

class Marca extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'marcas';

    protected $fillable = [
        'nome',
    ];

    public static function getAll(): Collection
    {
        return static::orderBy('nome')->get();
    }

    public static function getNames(): array
    {
        return static::orderBy('nome')->pluck('nome')->all();
    }
}
