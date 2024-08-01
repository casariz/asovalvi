<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'obligation_payments';

    protected $primaryKey = 'obligation_id';

    public $timestamps = false;

    public function created_by(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}