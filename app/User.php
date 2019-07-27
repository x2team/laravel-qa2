<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $appends = ['url', 'avatar'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function questions(){
        return $this->hasMany("App\Question");
    }

    public function getUrlAttribute()
    {
        //return route("questions.show", $this->id);
        return "#";
    }
    
    public function answers()
    {
        return $this->hasMany("App\Answer");
    }

    public function getAvatarAttribute()
    {
        $email = $this->email;
        // $default = "https://www.somewhere.com/homestar.jpg";
        $size = 32;

        return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?s=" . $size;
        
    }

    public function favorites()
    {
        return $this->belongsToMany("App\Question", 'favorites')->withTimestamps(); // user_id, question_id
    }


    //LIEN KET VOTES DEN 2 bang QUESTIONS va ANSWERS
    public function voteQuestions()
    {
        return $this->morphedByMany("App\Question", 'votable');
    }

    public function voteAnswers()
    {
        return $this->morphedByMany("App\Answer", 'votable');
    }


    public function voteQuestion(Question $question , $vote)
    {
        $voteQuestions = $this->voteQuestions();
        
        return $this->_vote($voteQuestions, $question, $vote);
    }

    public function voteAnswer(Answer $answer, $vote)
    {
        $voteAnswers = $this->voteAnswers();

        return $this->_vote($voteAnswers, $answer, $vote);
        
    }

    private function _vote($relationship, $model, $vote)
    {
        if($relationship->where('votable_id', $model->id)->exists()){
            $relationship->updateExistingPivot($model, ['vote' => $vote]);
        }
        else{
            $relationship->attach($model, ['vote' => $vote]);
        }

        $model->load('votes');
        $downVotes = (int) $model->downVotes()->sum('vote');
        $upVotes = (int) $model->upVotes()->sum('vote');
        
        $model->votes_count = $upVotes + $downVotes;
        $model->save();

        return $model->votes_count;
    }
}
