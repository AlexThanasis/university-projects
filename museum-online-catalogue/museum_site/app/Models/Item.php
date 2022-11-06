<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'obtained', 'image'];

    public function comments(){
        // return $this->belongsTo(User::class, 'author_id');
        return $this->hasMany(Comment::class, 'item_id');
    }

    public function labels(){
        return $this->belongsToMany(Label::class);
    }
}
