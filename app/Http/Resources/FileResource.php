<?php

namespace App\Http\Resources;

use App\Services\StreamFiles;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
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
            'name'=>StreamFiles::stream('files/'.$this->name),
            'type'=>$this->type,
            'created_at'=>$this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
