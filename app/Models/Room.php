<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';
    protected $fillable = ['name'];


    public function users()
    {
        return $this->belongsToMany(User::class, 'rooms_users')->withPivot(['vote_value', 'voted']);
    }


    public function usersVoted()
    {
        return $this->belongsToMany(User::class, 'rooms_users')
            ->withPivot(['vote_value', 'voted'])
            ->wherePivot('voted', true);
    }

    public function usersVotedAvg()
    {
        return $this->belongsToMany(User::class, 'rooms_users')
            ->withPivot(['vote_value', 'voted'])
            ->wherePivot('voted', true)
            ->avg('vote_value');
    }
}
