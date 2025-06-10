<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'obligation_payments';

    protected $primaryKey = 'payment_id';

    public $timestamps = false;

    protected $fillable = [
        'obligation_id',
        'payment_date',
        'amount',
        'payment_description',
        'created_by',
        'creation_date',
        'status'
    ];

    public function obligation(): BelongsTo {
        return $this->belongsTo(Obligation::class, 'obligation_id', 'obligation_id');
    }

    public function created_by(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function status(): BelongsTo {
        return $this->belongsTo(State::class, 'status', 'status');
    }
}