<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\Message;

class MessageSeeder extends Seeder
{
    const SEEDS = 20;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Message::factory()->count(self::SEEDS)->create();
    }
}
