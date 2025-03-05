<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Define the fields that are mass-assignable
    protected $fillable = [
        'title',
        'description',
        'image_url',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
