<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Game extends Model
{
    use HasFactory;



    protected $fillable = ['slug', 'title', 'description', 'thumbnail', 'upload_timestamp', 'author', 'score_count', 'popular'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
