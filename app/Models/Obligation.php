<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Obligation extends Model
{
    use HasFactory;

    protected $table = 'obligations';

    protected $primaryKey = 'obligation_id';

    public $timestamps = false;

    protected $fillable = [
        'obligation_id',
        'obligation_description',
        'category_id',
        'server_name',
        'quantity',
        'period',
        'alert_time',
        'created_by',
        'last_payment',
        'expiration_date',
        'observations',
        'internal_reference',
        'reviewed_by',
        'review_date',
        'status'
    ];

    public function reviewed_by(): BelongsTo {
        return $this->belongsTo(User::class, 'reviewed_by', 'id');
    }

    public function created_by(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function status(): BelongsTo {
        return $this->belongsTo(State::class, 'status', 'status');
    }

    public function payments() {
        return $this->hasMany(Payment::class, 'obligation_id', 'obligation_id');
    }
}