<?php

namespace App\Traits;

use App\Models\Location;

trait HasLocation
{
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function getFullLocationAttribute()
    {
        return $this->location()->withTrashed()->first()->location . ' ' . $this->location;
    }
}
