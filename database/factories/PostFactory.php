<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = User::pluck('id')->toArray();
        return [
            'title' => $this->faker->colorName(),
            'content' => $this->faker->text(200),
            'contact_person' => json_encode(['contact_name' => $this->faker->name(), 'contact_number' => $this->faker->phoneNumber()]),
            'location' => $this->faker->latitude(),
            'price' => $this->faker->numberBetween(1000, 1000000),
            'expired_time'=> $this->faker->dateTimeBetween('now','+10 days'),
            'created_by' => $users[array_rand($users)],
            'picture' => null,            
        ];
    }
}
