<?php

namespace App\Http\Controllers\Api\v1;

use Throwable;
use App\Models\Room;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\room\RoomRequest;
use App\Http\Requests\Api\v1\room\JoinMeRequest;
use App\Http\Resources\AllVoteColletion;

class RoomController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->successResponse('ok', 200, 'all rooms', [
            'rooms' => Room::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoomRequest $request)
    {
        $room = Room::create($request->validated());
        return $this->successResponse('ok', 201, 'Room created', [
            'room' => $room
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Room $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $room = Room::find($id);
        if ($room) {
            return $this->successResponse('ok', 200, 'Room to view', [
                'room' => $room->users
            ]);
        }
        return $this->errorResponse('error', 404, "The Room with id $id does not exist");
    }

    /**
     * joinMe
     *
     * @param JoinMeRequest $request
     * @return Response
     */
    public function joinMe(JoinMeRequest $request)
    {
        try {
            $room = Room::find($request->room_id);
            if (!$room) {
                return $this->errorResponse('error', 404, "The Room with id $request->room_id does not exist");
            }

            $user = User::find($request->user_id);
            if (!$user) {
                return $this->errorResponse('error', 404, "The User with id $request->user_id does not exist");
            }

            // attach user to the room
            $check_user = DB::table('rooms_users')->where([
                'room_id' => $room->id,
                'user_id' => $user->id,
            ])->first();

            if ($check_user) {
                return $this->errorResponse('error', 400, "The user is joined to the room");
            }

            $room->users()->attach($user->id, [
                'vote_value' => 0,
                'voted' => false
            ]);

            return $this->successResponse('ok', 202, 'The user was join to the room successfully', [
                'user_joined' => $user,
                'room_id' => $room->id
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * giveVote
     *
     * @param JoinMeRequest $request
     * @return Response
     */
    public function giveVote(JoinMeRequest $request)
    {
        try {
            $room = Room::find($request->room_id);
            if (!$room) {
                return $this->errorResponse('error', 404, "The Room with id $request->room_id does not exist");
            }

            $user = User::find($request->user_id);
            if (!$user) {
                return $this->errorResponse('error', 404, "The User with id $request->user_id does not exist");
            }

            if (!$request->vote_value) {
                return $this->errorResponse('error', 400, "The vote with value {$request->vote_value} isn´t validated");
            }

            $room->users()->updateExistingPivot($user->id, [
                'vote_value' => $request->vote_value,
                'voted' => true
            ]);

            return $this->successResponse('ok', 201, 'The User Vote Isssue', [
                'message' => "Thank you, you have voted - value: {$request->vote_value}",
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    /**
     * UserRoomVote
     *
     * @return void
     */
    public function UserRoomVote()
    {
        $id = request()->get('room_id');
        $room = Room::find($id);

        if ($room) {
            $usersVote = AllVoteColletion::collection($room->usersVoted);
            $avgVotes = collect($usersVote)->avg('vote_value');
            return $this->successResponse('ok', 200, 'Users Room Vote', [
                'users' => $usersVote,
                'n_users' => count($usersVote),
                'avg_votes' => $avgVotes
            ]);
        }
        return $this->errorResponse('error', 404, "The Room with id $id does not exist");
    }
}
