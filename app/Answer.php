<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['body', 'user_id'];

    public function question()
    {
        return $this->belongsTo("App\Question");
    }

    public function user()
    {
        return $this->belongsTo("App\User");
    }

    public function getBodyHtmlAttribute()
    {
        return \Parsedown::instance()->text($this->body);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function($answer){
            $answer->question->increment('answers_count');
        });

        static::deleted(function($answer){
            $question = $answer->question;
            $answer->question->decrement('answers_count');
            if($question->best_answer_id == $answer->id){
                $question->best_answer_id = NULL;
                $question->save();
            }
        });

        // static::saved(function($answer){
        //     echo "Answer saved\n";
        // });

    }

    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute()
    {
        return $this->id == $this->question->best_answer_id ? 'vote-accepted' : '';
    }
}
