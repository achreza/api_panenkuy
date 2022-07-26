<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($value) {
            return [
                'id' => $value->id,
                'title' => $value->title,
                'content' => $value->content,
                'contact_person' => $value->contact_person,
                'location' => $value->location,
                'price' => $value->price,
                'expired_time' => $value->expired_time,
                'created_by' => new UserResource($value->user()->first()),
                'picture' => $value->picture,
                'comments' => $value->comments()->select(['content','created_by'])->with('user:id,fullname')->get()
            ];
        });
    }
}
