<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingRoomsTable extends Migration
{
    protected $table = 'meeting_rooms';

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
                $table->string('room_name');
                $table->integer('created_by')->unsigned()->nullable()->default(1);
                $table->timestamp('created_at')->useCurrent();
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
