<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToDo extends Model
{
    protected $fillable = ['title', 'user_id'];

    public function getMyTodos($userId)
    {
        return self::where('user_id', $userId)->get();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
