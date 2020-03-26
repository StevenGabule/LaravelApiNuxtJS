<?php

namespace App\Http\Controllers\Designs;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Design;
use App\Http\Resources\DesignResource;
use App\Repositories\Contracts\IDesign;


class DesignController extends Controller
{
    protected $designs;
    
    public function __construct(IDesign $designs) {
        $this->designs = $designs;
    }
    
    public function index(): AnonymousResourceCollection
    {
        $designs = $this->designs->all();
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
            'tags' => ['required']
        ]);

        $this->designs->update($id, [
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

        $this->designs->delete();

        return response()->json(['message' => 'Recored Deleted.'], 200);
    }
}
