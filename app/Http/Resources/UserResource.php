<?php

namespace App\Http\Resources;
use App\Http\Resources\PivotResource;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'registration_details' => new PivotResource($this->pivot),
        ];
    }
}
