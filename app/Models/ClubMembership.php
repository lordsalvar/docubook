<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClubMembership extends Model
{
    protected $table = 'club_memberships';

    protected $fillable = [
        'club_id',
        'user_id',
        'designation',
        'status',
    ];
    
    protected $casts = [
        'joined_date' => 'date',
    ];


    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'club_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
