<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'info'=>$this->info,
            'category_id'=>$this->category_id,
            'category'=>CategoryResource::make($this->whenLoaded('category')),
            'file'=>FileResource::make($this->whenLoaded('file')),
            'created_at'=>$this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
