<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Subdivision extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'type',
        'batches_availables',
        'lowest_price',
        'lowest_surface',
        'address',
        'lat',
        'lon',
        'description',
        'amenities',
        'soon',
    ];

    protected $casts = [
        'amenities' => 'array'
    ];

    //relationships
    public function batches() :HasMany
    {
        return $this->hasMany(Batch::class);
    }
}
