<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Design;
use App\Http\Resources\DesignResource;
use Str;
class DesignController extends Controller
{
    public function update(Request $request, $id) {
        
        $design = Design::findOrFail($id);

        $this->authorize('update', $design);
        
        $this->validate($request, [
            'title' => ['required', 'unique:designs,title,'.$id],
            'description' => ['required', 'string', 'min:20', 'max:140'],
            'tags' => ['required']
        ]);

        $design->update([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'is_live' => !$design->upload_successful ? false : $request->is_live,
        ]);
        // apply the tags
        $design->retag($request->tags);
        return new DesignResource($design);
    }

    public function destroy($id) 
    {
        $design = Design::findOrFail($id);

        $this->authorize('delete', $design);

        foreach (['thumbnail', 'large', 'original'] as $size) {
            $d = $design->disk;
            $file_path = "uploads/designs/{$size}/{$design->image}";
            if (\Storage::disk($d)->exists($file_path)) {
                \Storage::disk($d)->delete($file_path);
            }
        }

        $design->delete();

        return response()->json(['message' => 'Recored Deleted.'], 200);
    }
}
