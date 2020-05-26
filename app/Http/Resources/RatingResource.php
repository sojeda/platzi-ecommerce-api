<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Laraveles\Rating\Models\Rating;

class RatingResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     * @see Rating
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'score' => $this->score,
            'comments' => $this->comments,
            'rateable' => $this->rateable,
            'qualifier' => $this->qualifier,
            'createdAt' => $this->created_at,
            'approved_at' => $this->approved_at
        ];
    }
}
