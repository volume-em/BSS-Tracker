<?php

namespace App\Models;

use App\Traits\HasLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BioSample extends Model
{
    use HasFactory, HasLocation, SoftDeletes;

    protected $with = ['loggerName', 'location'];

    public function loggerName()
    {
        return $this->belongsTo(LoggerName::class)->withTrashed();
    }

    public function project()
    {
        return $this->belongsTo(Project::class)->withTrashed();
    }

    public function samples()
    {
        return $this->hasMany(Sample::class)->withTrashed();
    }
}
