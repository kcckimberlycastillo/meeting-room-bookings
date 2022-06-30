<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MeetingRoom;
use Carbon\Carbon;

class MeetingRoomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rooms = MeetingRoom::first();
        $defaultDate = Carbon::now()->format('Y-m-d H:i:s');

        $items = [
            [ 
                'room_name' => 'Auckland', 
                'created_at' => $defaultDate, 
                'created_by' => 1, 
            ],
            [ 
                'room_name' => 'Christchurch', 
                'created_at' => $defaultDate, 
                'created_by' => 1, 
            ],
            [ 
                'room_name' => 'Dunedin', 
                'created_at' => $defaultDate, 
                'created_by' => 1, 
            ],
        ];

        if (empty($rooms)) {
            foreach ($items as $item) {
                MeetingRoom::updateOrCreate(['room_name' => $item['room_name']], $item);
            }
        }
    }
}
