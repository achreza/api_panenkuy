<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'contact_person' => $this->contact_person,
            'location' => $this->location,
            'price' => $this->price,
            'expired_time' => $this->expired_time,
            'created_by' => new UserResource($this->user()->first()),
            'picture' => $this->picture,
            'comments' => $this->comments()->select(['content','created_by'])->with('user:id,fullname')->get()
        ];
    }
}
