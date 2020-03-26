<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\IComment;
use App\Models\Comment;

class CommentRepository extends BaseRepository implements IComment
{
    public function model()
    {
        return Comment::class;
    }
}
