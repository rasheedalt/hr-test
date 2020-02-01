<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->title,
            'description' => $this->description,
            'cost' => $this->cost,
            'user' =>  UserResource::collection($this->user),
            // 'pivot' => $this->user->pivot->created_at
        ];
    }
}
