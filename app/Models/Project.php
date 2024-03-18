<?php

namespace App\Models;

use App\Traits\HasFullTextSearch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, HasFullTextSearch, SoftDeletes;

    protected $with = ['investigator'];

    protected static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            $attributes = collect([$model->load('investigator')->toArray()])->map(function ($record) {
                return [
                    strtolower($record['uid']),
                    strtolower($record['name']),
                    strtolower($record['investigator']['name'])
                ];
            })->first();

            Search::insert([
                'model' => $model::class,
                'model_id' => $model->id,
                'model_title' => $model->uid,
                'attributes' => json_encode($attributes)
            ]);
        });
    }

    public function investigator()
    {
        return $this->belongsTo(Investigator::class)->withTrashed();
    }

    public function bioSamples()
    {
        return $this->hasMany(BioSample::class)->withTrashed();
    }
}
