<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['title', 'body'];

    public function user(){
        return $this->belongsTo("App\User");
    }

    public function setTitleAttribute($value){
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = str_slug($value);
    }

    public function getUrlAttribute()
    {
        return route("questions.show", ['slug' => $this->slug, 'id' => $this->id]);
    }

    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute()
    {
        if($this->answers_count>0){
            if($this->best_answer_id){
                return "ansewerd-accepted";
            }
            return "answered";
        }
        return "unanswered";
    }

    public function getBodyHtmlAttribute()
    {
        return \Parsedown::instance()->text($this->body);
    }

    public function answers()
    {
        return $this->hasMany('App\Answer')->orderBy('votes_count', 'DESC'); //C1: Sort ben RouteServiceProvider, day la C2
    }

    public function acceptBestAnswer(Answer $answer)
    {
        $this->best_answer_id = $answer->id;
        $this->save();
    }

    public function favorites()
    {
        return $this->belongsToMany("App\User", 'favorites')->withTimestamps(); //, 'question_id', 'user_id');
    }

    public function isFavorited()
    {
        return $this->favorites()->where('user_id', auth()->id())->count() > 0;
    }

    public function getIsFavoritedAttribute() //Attribute dung de dua ra view su dung
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    public function votes()
    {
        return $this->morphToMany("App\User", 'votable');
    }

    //
    public function upVotes()
    {
        return $this->votes()->wherePivot('vote', 1); 
    }
    public function downVotes()
    {
        return $this->votes()->wherePivot('vote', -1); 
    }
}
