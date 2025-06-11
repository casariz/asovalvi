<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'position',
        'phone',
        'status'
    ];

    protected $hidden = [
        'password',
    ];

    // Relaciones con tasks
    public function created_tasks(): HasMany {
        return $this->hasMany(Task::class, 'created_by', 'id');
    }

    public function assigned_tasks(): HasMany {
        return $this->hasMany(Task::class, 'assigned_to', 'id');
    }

    public function reviewed_tasks(): HasMany {
        return $this->hasMany(Task::class, 'reviewed_by', 'id');
    }
}