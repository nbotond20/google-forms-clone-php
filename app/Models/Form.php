<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    // Fillable fields
    protected $fillable = [
        'title',
        'expires_at',
        'auth_required',
        'created_by',
        'link',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }
}
