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

    protected $primaryKey = 'meeting_id';

    protected $fillable = [
        'status'
    ];

    public function user_id(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}