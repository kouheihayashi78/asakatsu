<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WakeUpRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recorded_at',
        'is_achieved',
    ];

    protected function casts(): array
    {
        return [
            'recorded_at' => 'datetime',
            'is_achieved' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
