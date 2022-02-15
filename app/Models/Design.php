<?php

namespace App\Models;

use App\Models\Traits\Likeable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Support\Facades\Storage;

class Design extends Model
{
    use Taggable, Likeable;

    protected $fillable = [
        'user_id',
        'team_id',
        'image',
        'title',
        'description',
        'slug',
        'close_to_comment',
        'is_live',
        'upload_successful',
        'disk',
    ];

    protected $casts = [
        'is_live' => 'boolean',
        'upload_successful' => 'boolean',
        'close_to_comments' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->orderBy('created_at', 'asc');
    }

    public function getImagesAttribute(): array
    {
        return [
            'thumbnail' => $this->getImagePath('thumbnail'),
            'large' => $this->getImagePath('large'),
            'original' => $this->getImagePath('original'),
        ];
    }

    protected function getImagePath($size)
    {
        return Storage::disk($this->disk)->url("uploads/designs/{$size}/" . $this->image);
    }
}
