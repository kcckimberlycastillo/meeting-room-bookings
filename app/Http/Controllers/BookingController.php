<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeetingRoomBookings;
use Illuminate\Support\Facades\Auth;
use Validator;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = MeetingRoomBookings::with(['user', 'meeting_room'])->whereDeletedAt(NULL)->get()->toArray();
        
        return response()->json($results);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): object
    {
        $validator = Validator::make($request->all(), [
            'roomId' => 'required',
            'bookingDate' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json(['message' => $validator->errors()->all()], 400);
        } 

        $existing = MeetingRoomBookings::where([
            ['room_id', $request->roomId],
            ['booking_date', $request->bookingDate],
            ['booking_time_from', $request->startTime],
            ['booking_time_to', $request->endTime],
        ]);

        if ($existing->count() > 0) {
            return response()->json(['message' => 'Date and time for this room is already taken!'], 400);
        }

        $result = MeetingRoomBookings::insert([
            'room_id' => $request->roomId,
            'user_id' => $request->user,
            'booking_date' => $request->bookingDate,
            'booking_time_from' => $request->startTime,
            'booking_time_to' => $request->endTime,
            'created_by' => $request->user,
        ]);

        if (!$result){
            return response()->json(['message' => 'Something went wrong. Please contact the administrator'], 500);
        } 

        return response()->json(['message' => 'Booking has been saved.'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): object
    {
        $validator = Validator::make($request->all(), [
            'roomId' => 'required',
            'bookingDate' => 'required',
        ]);
        
        if ($validator->fails()){
            return response()->json(['message' => $validator->errors()->all()], 400);
        } 

        $booking = MeetingRoomBookings::find($id);

        $booking->room_id = $request->roomId;
        $booking->booking_date = $request->bookingDate;
        $booking->booking_time_from = $request->startTime;
        $booking->booking_time_to = $request->endTime;
        $booking->updated_by = $request->user;

        if(!$booking->update()){
            return response()->json(['message' => 'Something went wrong. Please contact the administrator'], 500);
        }

        return response()->json(['message' => 'Booking was successfully updated.'], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function softDelete(Request $request): object
    {
        if($request->rows){
            foreach($request->rows as $item){
                $booking = MeetingRoomBookings::find($item['id']);

                $booking->deleted_at = date('Y-m-d H:i:s');
                $booking->updated_by = $request->user;

                if(!$booking->save()){
                    return response()->json(['message' => 'Something went wrong. Please contact the administrator'], 500);
                }
            }
        }

        return response()->json(['message' => 'Booking was successfully deleted.'], 200);
    }
}
