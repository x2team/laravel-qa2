<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use VotableTrait;
    
    protected $fillable = ['title', 'body'];

    protected $appends = ['created_date', 'is_favorited', 'favorites_count', 'body_html']; //lay gia tri tu getCreatedDateAttribute, su dung qua Vue

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
                return "answered-accepted";
            }
            return "answered";
        }
        return "unanswered";
    }

    public function getBodyHtmlAttribute()
    {
        return clean($this->bodyHtml());
    }

    public function answers()
    {
        return $this->hasMany('App\Answer');//->orderBy('votes_count', 'DESC'); //C1: Sort ben RouteServiceProvider, day la C2
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
    //Model ket noi : Su dung ben VotableTrait.php
    // public function votes()
    // {
    //     return $this->morphToMany("App\User", 'votable');
    // }

    // //
    // public function upVotes()
    // {
    //     return $this->votes()->wherePivot('vote', 1); 
    // }
    // public function downVotes()
    // {
    //     return $this->votes()->wherePivot('vote', -1); 
    // }

    //Su dung ham nay: $post->excerpt;
    public function getExcerptAttribute($length)
    {
        return  $this->excerpt(250);
    }
    //*********************************** */

    //Goi ham nay: $post->excerpt(350);
    public function excerpt($length)
    {
        return  \Str::limit(strip_tags($this->bodyHtml()), $length, '...');
    }
    public function bodyHtml()
    {
        return \Parsedown::instance()->text($this->body);
    }
}
