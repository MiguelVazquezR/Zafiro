<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Batch extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'price',
        'surface',
        'address',
        'maps_url',
        'description',
        'subdivision_id',
    ];

    //relationships
    public function subdivision() :BelongsTo
    {
        return $this->belongsTo(Subdivision::class);
    }
}
