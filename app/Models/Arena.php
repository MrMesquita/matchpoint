<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arena extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'street',
        'number',
        'neighborhood',
        'city',
        'state',
        'zip_code',
        'admin_id'
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function courts(): HasMany
    {
        return $this->hasMany(Court::class);
    }
}
