<?php

namespace Database\Seeders;

use App\Models\ActivityType;
use Illuminate\Database\Seeder;

class ActivityTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['name' => 'Broadcast WhatsApp'],
            ['name' => 'Follow-up'],
            ['name' => 'Sebar Brosur'],
            ['name' => 'Visit'],
            ['name' => 'Chat WhatsApp'],
            ['name' => 'DM Instagram'],
            ['name' => 'Chat Marketplace FB'],
            ['name' => 'Iklan'],
            ['name' => 'Chat Tokopedia'],
            ['name' => 'Chat Shopee'],
        ];

        foreach ($types as $type) {
            ActivityType::create($type);
        }
    }
}