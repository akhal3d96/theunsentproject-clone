<?php

namespace Database\Factories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'to' => $this->faker->firstName(),
            'body' => substr(
                $this->faker->paragraph(),
                0,
                \App\Http\Controllers\MessageController::MAX_BODY_LENGTH
            ),
            'color' => $this->faker->safeColorName(),
        ];
    }
}
