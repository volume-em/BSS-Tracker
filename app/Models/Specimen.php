<?php

namespace App\Models;

use App\Traits\HasLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specimen extends Model
{
    use HasFactory, HasLocation, SoftDeletes;

    protected $with = ['loggerName', 'location', 'substrateType', 'imagingApproach'];

    public function loggerName()
    {
        return $this->belongsTo(LoggerName::class)->withTrashed();
    }

    public function substrateType()
    {
        return $this->belongsTo(SubstrateType::class)->withTrashed();
    }

    public function imagingApproach()
    {
        return $this->belongsTo(ImagingApproach::class)->withTrashed();
    }

    public function sample()
    {
        return $this->belongsTo(Sample::class)->withTrashed();
    }
}
