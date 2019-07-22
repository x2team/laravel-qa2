<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use VotableTrait;
    
    protected $fillable = ['body', 'user_id'];
    protected $appends = ['created_date', 'body_html']; //lay gia tri tu getCreatedDateAttribute, de su dung qua Vue

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
        return clean(\Parsedown::instance()->text($this->body));
    }

    public static function boot()
    {
        parent::boot();

        static::created(function($answer){
            $answer->question->increment('answers_count');
        });

        static::deleted(function($answer){
            // C1
            // $question = $answer->question;
            // $answer->question->decrement('answers_count');
            // if($question->best_answer_id == $answer->id){
            //     $question->best_answer_id = NULL;
            //     $question->save();
            // }

            //C2: Tao lien ket foreign best_answer_id tren questions table
            // khi xoa trung cau tra loi co best_answer_id se tu dong set NULL ben bang questions
            $answer->question->decrement('answers_count');

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
        return $this->isBest() ? 'vote-accepted' : '';
    }

    public function getIsBestAttribute()
    {
        return $this->isBest();
    }

    public function isBest()
    {
        return $this->id == $this->question->best_answer_id;
    }

    
}
