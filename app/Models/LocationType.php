<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Location;

class LocationType extends Model {
    use HasFactory;

    protected $guarded = [];

    public function locations(): BelongsToMany {
        return $this->belongsToMany(Location::class, 'locations_locations_types', 'locationTypeId', 'locationId');
    }
}
