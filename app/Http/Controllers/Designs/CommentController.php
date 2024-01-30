<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Repositories\Contracts\IComment;
use App\Repositories\Contracts\IDesign;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * @var IComment
     */
    protected $comments, $designs;

    public function __construct(IComment $comments, IDesign $designs)
    {
        $this->comments = $comments;
        $this->designs = $designs;
    }

    public function store(Request $request, $designId)
    {
        $this->validate($request, ['body' => ['required']]);

        $comment = $this->designs->addComment($designId, [
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);

        return new CommentResource($comment);
    }

    public function update(Request $request, $id)
    {
        $comment = $this->comments->find($id);
        $this->authorize('update', $comment);
        $this->validate($request, ['body' => ['required']]);
        $comment = $this->comments->update($id, ['body' => $request->body]);
        return new CommentResource($comment);
    }

    public function destroy($id)
    {
        $comment = $this->comments->find($id);
        $this->authorize('delete', $comment);
        $this->comments->delete($id);
        return response()->json(['message' => 'Item Deleted'], 200);
    }
}
