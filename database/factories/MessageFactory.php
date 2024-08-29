<?php

namespace Database\Factories;

use App\Models\ChatMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = ChatMessage::class;

    public function definition()
    {
        return [
            // Định nghĩa các thuộc tính mặc định cho Note
            'id' => $this->faker->unique()->randomNumber(),
            'sender_id' => $this->faker->randomNumber(),
            'message' => $this->faker->paragraph,
            // Các thuộc tính khác của model Note
        ];
    }
}
