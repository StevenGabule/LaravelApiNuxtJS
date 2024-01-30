<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Team extends Model
{
  protected $fillable = ['name', 'owner_id', 'slug'];

  protected static function boot(): void
  {
    parent::boot();

    // when team is created, add current user as team member
    static::created(static function ($team) {
      $team->members()->attach(auth()->id());
    });

    static::deleting(static function ($team) {
      $team->members()->sync([]);
    });
  }

  public function owner(): BelongsTo
  {
    return $this->belongsTo(User::class, 'owner_id');
  }

  public function members()
  {
    return $this->belongsToMany(User::class)->withTimestamps();
  }

  public function designs(): HasMany
  {
    return $this->hasMany(Design::class);
  }

  public function hasUser(User $user): bool
  {
    return $this->members()->where('user_id', $user->id)->first() ? true : false;
  }

  public function invitations(): HasMany
  {
    return $this->hasMany(Invitation::class);
  }

  public function hasPendingInvite($email): bool
  {
    return (bool) $this->invitations()->where('recipient_email', $email)->count();
  }
}
