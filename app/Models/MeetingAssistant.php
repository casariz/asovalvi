<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingAssistant extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'meeting_assistants';

    protected $primaryKey = ['meeting_id', 'user_id'];
    public $incrementing = false;

    protected $fillable = [
        'meeting_id',
        'user_id',
        'status'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function meeting(): BelongsTo {
        return $this->belongsTo(Meeting::class, 'meeting_id', 'meeting_id');
    }

    public function status(): BelongsTo {
        return $this->belongsTo(State::class, 'status', 'status');
    }
}