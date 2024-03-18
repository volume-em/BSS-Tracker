<?php

namespace App\Models;

use App\Traits\HasLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sample extends Model
{
    use HasFactory, HasLocation, SoftDeletes;

    protected $with = ['loggerName', 'location'];

    public function loggerName()
    {
        return $this->belongsTo(LoggerName::class)->withTrashed();
    }

    public function bioSample()
    {
        return $this->belongsTo(BioSample::class)->withTrashed();
    }

    public function specimens()
    {
        return $this->hasMany(Specimen::class)->withTrashed();
    }
}
