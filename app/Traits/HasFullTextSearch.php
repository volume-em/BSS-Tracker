<?php

namespace App\Traits;

use App\Models\Search;
use Filament\GlobalSearch\GlobalSearchResult;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait HasFullTextSearch
{
    public static function getGloballySearchableAttributes(): array
    {
        return ['model'];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return Search::query();
    }

    public static function getGlobalSearchResultsQuery(string $search): Builder
    {
        $query = static::getGlobalSearchEloquentQuery();

        $model = addslashes(self::class);

        $query = $query->whereRaw('model = ?', Str::startsWith(self::class, 'App\\Models\\') ? $model : self::$model);

        return $query->where('attributes', 'LIKE', '%' . strtolower($search) . '%');
    }

    public static function getGlobalSearchResults(string $search): Collection
    {
        return self::getGlobalSearchResultsQuery($search)
            ->limit(static::getGlobalSearchResultsLimit())
            ->get()
            ->map(function (Model $record): ?GlobalSearchResult {
                $record['id'] = $record['model_id'];

                $url = static::getGlobalSearchResultUrl($record);

                if (blank($url)) {
                    return null;
                }

                return new GlobalSearchResult(
                    title: static::getGlobalSearchResultTitle($record),
                    url: $url,
                    details: static::getGlobalSearchResultDetails($record),
                    actions: static::getGlobalSearchResultActions($record),
                );
            })
            ->filter();
    }
}
