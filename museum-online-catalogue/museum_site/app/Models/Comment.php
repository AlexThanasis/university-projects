<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'user_id', 'item_id'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function item(){
        // return $this->belongsToMany(Category::class);
        return $this->belongsTo(Item::class, 'item_id');
    }
}
