<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;


class Organization extends Model
{
    use HasUlids;

    protected $fillable = [
        'name',
        'logo',
        'acronym',
        'club_type',
        'moderator',
        'dean',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(ClubMembership::class, 'club_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'club_memberships', 'club_id', 'user_id')
            ->withPivot('designation', 'status', 'joined_date')
            ->withTimestamps();
    }

    public function activeMembers(): HasMany
    {
        return $this->members()->where('status', 'active');
    }

    public function deans(): HasMany
    {
        return $this->members()->where('designation', 'dean');
    }

    public function moderators(): HasMany
    {
        return $this->members()->where('designation', 'moderator');
    }

    public function userWithDesignation(string $designation): BelongsToMany
    {
        return $this->user()->wherePivot('designation', $designation);
    }
}
