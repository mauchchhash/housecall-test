<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Drug extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rxcui', 'name', 'base_names', 'dose_form_group_names'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'base_names' => 'array',
        'dose_form_group_names' => 'array',
    ];

    /**
     * Get the user that the drug is prescribed to
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
