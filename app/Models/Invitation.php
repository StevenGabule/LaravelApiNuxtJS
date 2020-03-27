<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invitation extends Model
{
    protected $fillable = ['recipient_email', 'sender_id', 'team_id', 'token'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function recipient(): HasOne
    {
        return $this->hasOne(User::class, 'email', 'recipient_email');
    }

    public function sender(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'sender_id');
    }
}
