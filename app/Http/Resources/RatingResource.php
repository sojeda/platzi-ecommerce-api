<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'rate' => $this->rate,
            'comments' => $this->comments,
            'rateable' => $this->rateable,
            'qualifier' => $this->qualifier,
            'createdAt' => $this->created_at,
            'approved_at' => $this->approved_at
        ];
    }
}
