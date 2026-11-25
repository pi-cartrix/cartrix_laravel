<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use MongoDB\Laravel\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $connection = 'mongodb';

    protected $table = 'usuarios';

    protected $fillable = [
        'cnpj',
        'email',
        'name',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function findByEmail(string $email): ?self
    {
        return static::where('email', strtolower(trim($email)))->first();
    }

    public static function findByCnpj(string $cnpj): ?self
    {
        return static::where('cnpj', trim($cnpj))->first();
    }

    public function veiculos(): HasMany
    {
        return $this->hasMany(Veiculo::class, 'user_id', '_id');
    }
}
