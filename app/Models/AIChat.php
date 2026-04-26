<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIChat extends Model
{
    use HasFactory;

    protected $table = 'ai_chats';

    protected $fillable = [
        'user_id',
        'message',
        'response',
        'type',
        'has_image',
        'image_path',
    ];

    protected $casts = [
        'has_image' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
