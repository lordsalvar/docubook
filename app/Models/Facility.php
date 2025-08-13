<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    use HasUlids;

    protected $fillable = [
        'name',
        'code',
        'description',
        'capacity',
        'status',
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function totalCapacity(): ?int
    {
        if ($this->rooms->isNotEmpty()) {
            return $this->rooms->sum('capacity');
        }

        return $this->capacity;
    }
}
