<?php

namespace App\Http\Controllers\Designs;


use Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

use App\Http\Resources\DesignResource;
use App\Repositories\Contracts\IDesign;
use App\Repositories\Eloquent\Criteria\{EagerLoad, IsLive, LatestFirst, ForUser};

class DesignController extends Controller
{
    protected $designs;
    
    public function __construct(IDesign $designs) {
        $this->designs = $designs;
    }
    
    public function index(): AnonymousResourceCollection
    {
        $designs = $this->designs->withCriteria([
            new LatestFirst(),
            new IsLive(),
            new ForUser(1),
            new EagerLoad(['user', 'comments'])
        ])->all();
        return DesignResource::collection($designs);
    }

    public function findDesign($id): DesignResource
    {
        $design = $this->designs->find($id);
        return new DesignResource($design);
    }

    public function update(Request $request, $id): DesignResource
    {
        $design = $this->designs->find($id);
        $this->authorize('update', $design);
        $this->validate($request, [
            'title' => ['required', 'unique:designs,title,'.$id],
            'description' => ['required', 'string', 'min:20', 'max:140'],
            'tags' => ['required'],
            'team' => ['required_if:assign_to_team,true']
        ]);

        $this->designs->update($id, [
            'team_id' => $request->team,
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'is_live' => !$design->upload_successful ? false : $request->is_live,
        ]);

        // apply the tags
        $this->designs->applyTags($id, $request->tags);

        return new DesignResource($design);
    }

    public function destroy($id) 
    {
        $design = $this->designs->find($id);

        $this->authorize('delete', $design);

        foreach (['thumbnail', 'large', 'original'] as $size) {
            $d = $design->disk;
            $file_path = "uploads/designs/{$size}/{$design->image}";
            if (\Storage::disk($d)->exists($file_path)) {
                \Storage::disk($d)->delete($file_path);
            }
        }

        $this->designs->delete($id);

        return response()->json(['message' => 'Recored Deleted.'], 200);
    }

    public function like($id)
    {
        $this->designs->like($id);
        return response()->json(['message' => 'Successful'], 200);
    }

    public function checkIfUserHasLiked($designId)
    {
        $isLiked =  $this->designs->isLikedByUser($designId);
        return response()->json(['liked' => $isLiked], 200);
    }
}
