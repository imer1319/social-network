<?php

namespace Database\Factories;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FriendshipFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Friendship::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'recipient_id' => function () {
                return User::factory()->create();
            },
            'sender_id' => function () {
                return User::factory()->create();
            }
        ];
    }
}
