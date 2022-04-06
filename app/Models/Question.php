<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    // Fillable fields
    protected $fillable = [
        'question',
        'answer_type',
        'required',
    ];

    public function form() {
        return $this->belongsTo(Form::class);
    }

    public function choices() {
        return $this->hasMany(Choice::class);
    }

    public function answers() {
        return $this->hasMany(Answer::class);
    }
}
