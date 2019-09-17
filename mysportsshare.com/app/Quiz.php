<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $guarded = [];
    protected $table = 'Quiz';
    public function questions()
    {
        return $this->hasMany(Questions::class);
    }
}
