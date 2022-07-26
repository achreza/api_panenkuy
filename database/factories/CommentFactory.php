<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $posts = Post::pluck('id')->toArray();
        $users = User::pluck('id')->toArray();
        
        return [            
            'content' => $this->faker->colorName,
            'post_id' => $posts[array_rand($posts)],
            'created_by' => $users[array_rand($users)],
        ];
    }
}
