<?php

namespace App\Models;

use App\Enums\Designation;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClubMembership extends Model
{
    protected $table = 'club_memberships';

    use HasUlids;

    protected $fillable = [
        'user_id',
        'organization_id',
        'designation',
        'status',
        'joined_date',
    ];

    protected $casts = [
        'joined_date' => 'date',
        'designation' => Designation::class,
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
