<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\RoomResource;
use App\Room;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoomsController extends Controller
{
    public function create()
    {
        $customer = auth('api')->user();
        $room = Room::create([
            'code' => 'r'.str_random(),
            'owner_id' => $customer->id,
        ]);
        $room->customers()->attach($customer);
        return new RoomResource($room);
    }

    public function join($code)
    {
        $room = Room::where('code', $code)->firstOrFail();
        $customer = auth('api')->user();

        // you cannot join twice
        if($room->customers()->where('customer_id', $customer->id)->exists())
            return;

        $room->customers()->attach($customer);
        return response()->json(['message' => 'Success']);
    }

    public function kickUser($code, User $user)
    {
        $roomOwner = auth('api')->user();
        $room = Room::where('code', $code)->firstOrFail();

        if($room->owner_id != $roomOwner->id) {
            return response()->json(['error_message' => 'Not owner of the room']);
        }

        $room->customers()->detach($user->id);
        return response()->json(['message' => 'Success']);
    }

    public function recycle($code)
    {
        $roomOwner = auth('api')->user();
        $room = Room::where('code', $code)->firstOrFail();

        if($room->owner_id != $roomOwner->id) {
            return response()->json(['error_message' => 'Not owner of the room']);
        }

        $room->delete();
        return response()->json(['message' => 'Success']);
    }
}
