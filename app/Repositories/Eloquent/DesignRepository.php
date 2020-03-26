<?php

namespace App\Repositories\Eloquent;

use App\Models\Design;
use App\Repositories\Contracts\IDesign;


class DesignRepository extends BaseRepository implements IDesign
{
    public function model()
    {
        return Design::class;
    }

    public function applyTags($id, array $data)
    {
        $design = $this->find($id);
        $design->retag($data);
    }

    /**
     * @param $designId
     * @param array $data
     * @return mixed
     */
    public function addComment($designId, array $data)
    {
        $design = $this->find($designId);
        return $design->comments()->create($data);
    }

    public function like($id)
    {
        $design = $this->model->findOrFail($id);
        if ($design->isLikedByUser(auth()->id())) {
            $design->unLike();
        } else {
            $design->like();
        }
    }

    public function isLikedByUser($id)
    {
        $design = $this->model->findOrFail($id);
        return $design->isLikedByUser(auth()->id());
    }
}
