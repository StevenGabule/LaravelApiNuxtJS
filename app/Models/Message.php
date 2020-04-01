<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $touches = ['chat'];

    protected $fillable = ['user_id', 'chat_id', 'body', 'last_read'];

    public function getBodyAttribute($value)
    {
        if ($this->trashed()) {
            if (!auth()->check()) {
                return null;
            }
            return auth()->id() === $this->sender->id ? 'Message removed' : "{$this->sender->name} removed this message";
        }
        return $value;
    }

    public function chat() : BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function sender() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
