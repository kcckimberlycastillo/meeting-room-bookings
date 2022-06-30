<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingRoomBookings extends Model
{
    use HasFactory;

    protected $table = 'meeting_room_bookings';

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */
    public function meeting_room()
    {
        return $this->hasOne(MeetingRoom::class, 'id', 'room_id');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
