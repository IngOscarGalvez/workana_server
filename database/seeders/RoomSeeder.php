<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Room::create([
            'name' => 'Issue 1',
        ]);
        Room::create([
            'name' => 'Issue 2',
        ]);
        Room::create([
            'name' => 'Issue 3',
        ]);
        Room::create([
            'name' => 'Issue 4',
        ]);
        Room::create([
            'name' => 'Issue 5',
        ]);
    }
}
