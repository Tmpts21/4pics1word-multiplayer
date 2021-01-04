<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class Message extends Model
{

    protected $guarded = [];

    protected $appends = ['time_ago'];


    public function user() { 
        return $this->belongsTo('App\User');
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
