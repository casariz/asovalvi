<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingTopic extends Model
{
    use HasFactory;

    protected $table = 'meeting_topics';

    protected $primaryKey = 'topic_id';

    public $timestamps = false;

    protected $fillable = [
        'meeting_id',
        'type',
        'topic',
        'created_by',
        'creation_date',
        'status'
    ];

    public function meeting(): BelongsTo {
        return $this->belongsTo(Meeting::class, 'meeting_id', 'meeting_id');
    }

    public function created_by(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function status(): BelongsTo {
        return $this->belongsTo(State::class, 'status', 'status');
    }
}