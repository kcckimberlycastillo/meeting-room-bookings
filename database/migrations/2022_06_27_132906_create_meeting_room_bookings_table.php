<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingRoomBookingsTable extends Migration
{
    protected $table = 'meeting_room_bookings';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        if (!Schema::hasTable($this->table)) { 
            Schema::create($this->table, function (Blueprint $table) {
                $table->id();
                $table->string('room_id');
                $table->string('user_id');
                $table->date('booking_date');
                $table->string('booking_time_from');
                $table->string('booking_time_to');
                $table->mediumText('description')->nullable()->default('');
                $table->softDeletes();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')
                    ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
                $table->integer('created_by')->unsigned()->nullable()->default(1);
                $table->integer('updated_by')->unsigned()->nullable()->default(1);
            });
        }
    }    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
