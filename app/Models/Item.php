<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    /**
     * Get the mesa associated with the Item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mesa(): BelongsTo
    {
        return $this->belongsTo(Mesa::class);
    }
}
