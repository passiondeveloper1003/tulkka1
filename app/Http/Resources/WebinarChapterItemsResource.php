<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WebinarChapterItemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $file = $this->item->user_has_access;

        return [
            'can' => [
                  'view' => (!$this->resource->item->canViewError() and (($this->type == 'file' and $this->item->user_has_access) or ($this->type != 'file'))),
            ],
            'can_view_error' => $this->resource->item->canViewError(),
            'auth_has_read' => $this->item->read,
            //    'ff' => $this->resource->item->checkSequenceContent(apiAuth()),
            //  'id' => $this->id,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'link' => route($this->type . '.show', $this->item->id),
            $this->merge($this->resource->getItemResource()),
        ];
    }
}
