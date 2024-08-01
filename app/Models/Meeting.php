<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meeting extends Model
{
    use HasFactory;

    protected $table = 'meetings';

    protected $primaryKey = 'meeting_id';

    public $timestamps = false;

    protected $fillable = [
        'meeting_date',
        'start_hour',
        'called_by',
        'director',
        'secretary',
        'placement',
        'meeting_description',
        'empty_field',
        'topics',
        'created_by',
        'creation_date',
        'status'
    ];

    public function called_by(): BelongsTo {
        return $this->belongsTo(User::class, 'called_by', 'id');
    }

    public function director(): BelongsTo {
        return $this->belongsTo(User::class, 'director', 'id');
    }

    public function secretary(): BelongsTo {
        return $this->belongsTo(User::class, 'secretary', 'id');
    }

    public function created_by(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function status(): BelongsTo {
        return $this->belongsTo(State::class, 'status', 'status');
    }

    public function topics(): BelongsTo {
        return $this->belongsTo(MeetingTopic::class, 'meeting_id', 'meeting_id');
    }
}