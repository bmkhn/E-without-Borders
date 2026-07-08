<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function clubs(): HasMany
    {
        return $this->hasMany(Club::class);
    }

    public function regionalAdmin(): HasOne
    {
        return $this->hasOne(User::class, 'region_id')
            ->whereHas('roles', function ($q) {
                $q->where('name', 'regional-admin');
            });
    }
}
