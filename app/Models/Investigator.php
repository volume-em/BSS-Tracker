<?php

namespace App\Models;

use App\Traits\HasFullTextSearch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investigator extends Model
{
    use HasFactory, HasFullTextSearch, SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            $attributes = collect([$model->toArray()])->map(function ($record) {
                return [
                    strtolower($record['first_name']),
                    strtolower($record['middle_initial']),
                    strtolower($record['last_name']),
                    strtolower($record['email']),
                ];
            })->first();

            Search::insert([
                'model' => $model::class,
                'model_id' => $model->id,
                'model_title' => $model->name,
                'attributes' => json_encode($attributes)
            ]);
        });
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->middle_initial . ' ' . $this->last_name;
    }

    public function projects()
    {
        return $this->hasMany(Project::class)->withTrashed();
    }
}
