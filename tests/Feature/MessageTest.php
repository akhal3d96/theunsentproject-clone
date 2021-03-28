<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Database\Seeders\MessageSeeder;
use App\Http\Controllers\MessageController;
use App\Models\Message;

class MessageTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected $seeder = MessageSeeder::class;
    protected $seed = true;

    /**
     * Generate random string with length
     * 
     * @return string
     */
    private function randomLongString(int $length)
    {
        $bad_input_template = '[A-Za-z0-9]{$invalid_length}';
        $bad_input_regex = strtr($bad_input_template, [
            '$invalid_length' => $length
        ]);

        return $this->faker->regexify($bad_input_regex);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_messages_index()
    {
        $response = $this->get('/api/messages');
        $response->assertJsonPath('total', MessageSeeder::SEEDS);
    }

    public function test_messages_store()
    {
        $old_models_number = Message::count();

        $message = Message::factory()->make();
        $response = $this->postJson('/api/messages', $message->toArray());
        // print_r($message->toArray());
        // echo $response->getContent();
        $response->assertStatus(201);

        $new_models_number = Message::count();

        $this->assertGreaterThan(0, $new_models_number - $old_models_number);
    }

    public function test_messages_show()
    {
        $id = Message::inRandomOrder()->first()->id;
        $api_path = '/api/messages/' . $id;

        $response = $this->get($api_path);

        $response->assertJsonPath('id', $id);
    }

    public function test_messages_view_counter()
    {
        $message = Message::inRandomOrder()->first();
        $api_path = '/api/messages/' . $message->id;
        $this->get($api_path);
        $this->assertGreaterThan(0, $message->fresh()->views);
    }

    public function test_reject_empty_message_body_params()
    {
        $message = Message::factory()->make([
            'body' => ''
        ]);

        $response = $this->postJson('/api/messages', $message->toArray());

        $response->assertStatus(422);
    }

    public function test_reject_empty_message_to_params()
    {
        $message = Message::factory()->make([
            'to' => ''
        ]);

        $response = $this->postJson('/api/messages', $message->toArray());

        $response->assertStatus(422);
    }

    public function test_reject_empty_message_color_params()
    {
        $message = Message::factory()->make([
            'color' => ''
        ]);

        $response = $this->postJson('/api/messages', $message->toArray());

        $response->assertStatus(422);
    }

    public function test_reject_wrong_message_color_params()
    {
        $message = Message::factory()->make([
            'color' => $this->randomLongString(12)
        ]);

        $response = $this->postJson('/api/messages', $message->toArray());

        $response->assertStatus(422);
    }

    public function test_reject_long_message_body_params()
    {
        $message = Message::factory()->make([
            'body' => $this->randomLongString(MessageController::MAX_BODY_LENGTH + 1)
        ]);

        $response = $this->postJson('/api/messages', $message->toArray());

        $response->assertStatus(422);
    }

    public function test_reject_long_message_to_params()
    {
        $message = Message::factory()->make([
            'to' => $this->randomLongString(MessageController::MAX_TO_LENGTH + 1)
        ]);

        $response = $this->postJson('/api/messages', $message->toArray());

        $response->assertStatus(422);
    }
}
