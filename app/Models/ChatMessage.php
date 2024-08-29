<?php

namespace App\Models;

use App\Events\SentMessage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;
    protected $fillable = ['sender_id', 'message'];
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    protected static function boot()
    {
        parent::boot();
        static::created(function (ChatMessage $message) {
            event(new SentMessage($message));
        });
    }
}
