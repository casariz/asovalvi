<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    public $timestamps = false;

    protected $fillable = [
        'first_name',
        'last_name',
        'document_number',
        'user_type',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    public function status(): BelongsTo {
        return $this->belongsTo(State::class, 'status', 'status');
    }
}