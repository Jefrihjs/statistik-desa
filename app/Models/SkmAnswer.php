<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkmAnswer extends Model
{
    protected $fillable = [
        'skm_response_id',
        'skm_question_id',
        'nilai',
    ];

    public function response()
    {
        return $this->belongsTo(SkmResponse::class, 'skm_response_id');
    }

    public function question()
    {
        return $this->belongsTo(SkmQuestion::class, 'skm_question_id');
    }
}